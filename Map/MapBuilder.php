<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface;
use IDCI\Bundle\StepBundle\Path\PathBuilderInterface;
use IDCI\Bundle\StepBundle\Step\StepBuilderInterface;
use IDCI\Bundle\StepBundle\Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MapBuilder implements MapBuilderInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $steps;

    /**
     * @var array
     */
    private $paths;

    /**
     * @var FlowRecorderInterface
     */
    private $flowRecorder;

    /**
     * @var StepBuilderInterface
     */
    private $stepBuilder;

    /**
     * @var PathBuilderInterface
     */
    private $pathBuilder;

    /**
     * @var Environment
     */
    private $merger;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * Creates a new map builder.
     */
    public function __construct(
        FlowRecorderInterface $flowRecorder,
        StepBuilderInterface $stepBuilder,
        PathBuilderInterface $pathBuilder,
        Environment $merger,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        string $name = null,
        array $data = [],
        array $options = []
    ) {
        $this->name = $name;
        $this->data = $data;
        $this->options = self::resolveOptions($options);
        $this->flowRecorder = $flowRecorder;
        $this->stepBuilder = $stepBuilder;
        $this->pathBuilder = $pathBuilder;
        $this->merger = $merger;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->steps = [];
        $this->paths = [];
    }

    /**
     * Resolve options.
     */
    public static function resolveOptions(array $options = []): array
    {
        $resolver = new OptionsResolver();
        $resolver
            ->setDefaults([
                'form_action' => null,
                'first_step_name' => null,
                'final_destination' => null,
                'display_step_in_url' => false,
                'reset_flow_data_on_init' => false,
            ])
            ->setAllowedTypes('display_step_in_url', ['bool'])
            ->setAllowedTypes('reset_flow_data_on_init', ['bool'])
        ;

        return $resolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function getOption(string $name, $default = null)
    {
        return $this->hasOption($name) ? $this->options[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function addStep(string $name, string $type, array $options = []): MapBuilderInterface
    {
        $this->steps[$name] = [
            'type' => $type,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addPath(string $type, array $options = []): MapBuilderInterface
    {
        $this->paths[] = [
            'type' => $type,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMap(Request $request): MapInterface
    {
        return $this->build($request);
    }

    /**
     * Build the map.
     */
    private function build(Request $request): MapInterface
    {
        // TODO: Use a MapConfig as argument instead of an array.
        $map = new Map([
            'name' => $this->name,
            'footprint' => $this->generateFootprint(),
            'data' => $this->merge($this->data),
            'options' => $this->options,
        ]);

        // Build steps before paths !
        $this->buildSteps($map, $request);
        $this->buildPaths($map, $request);

        return $map;
    }

    /**
     * Build steps into the map.
     */
    private function buildSteps(MapInterface $map, Request $request)
    {
        $vars = [];
        $flow = $this->flowRecorder->getFlow($map, $request);

        if (null !== $flow) {
            $vars['flow_data'] = $flow->getData();
        }

        foreach ($this->steps as $name => $parameters) {
            $stepOptions = $this->merge($parameters['options'], $vars);

            $step = $this->stepBuilder->build($name, $parameters['type'], $stepOptions);

            if (null !== $step) {
                $map->addStep($name, $step);
            }

            if (null === $map->getFirstStepName() || $step->isFirst()) {
                $map->setFirstStepName($name);
            }
        }
    }

    /**
     * Build paths into the map.
     */
    private function buildPaths(MapInterface $map, Request $request)
    {
        foreach ($this->paths as $parameters) {
            $path = $this->pathBuilder->build(
                $parameters['type'],
                $parameters['options'],
                $map->getSteps()
            );

            if (null !== $path) {
                $map->addPath(
                    $path->getSource()->getName(),
                    $path
                );
            }
        }
    }

    /**
     * Generate a map unique footprint based on its name, steps and paths.
     */
    private function generateFootprint(): string
    {
        return md5($this->name.json_encode($this->steps).json_encode($this->paths));
    }

    /**
     * Merge options with the SecurityContext (user) and the session (session).
     */
    private function merge(array $options = [], array $vars = []): array
    {
        $vars['session'] = $this->session->all();
        $vars['user'] = null !== $this->tokenStorage->getToken() ?
            $this->tokenStorage->getToken()->getUser() :
            null
        ;

        return $this->mergeValue($options, $vars, false);
    }

    /**
     * Merge a value.
     */
    private function mergeValue($value, array $vars = [], bool $try = true)
    {
        // Handle array case.
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                // Do not merge if ending with '|raw'.
                if ('|raw' == substr($k, -4)) {
                    $value[substr($k, 0, -4)] = $v;
                    unset($value[$k]);
                // Do not merge events parameters.
                } elseif ('events' !== $k) {
                    $value[$k] = $this->mergeValue($v, $vars, $try);
                }
            }
            // Handle object case.
        } elseif (is_object($value)) {
            $class = new \ReflectionClass($value);
            $properties = $class->getProperties();

            foreach ($properties as $property) {
                $property->setAccessible(true);

                $property->setValue(
                    $value,
                    $this->mergeValue(
                        $property->getValue($value),
                        $vars
                    )
                );
            }
            // Handle string case.
        } elseif (is_string($value)) {
            try {
                $template = $this->merger->createTemplate($value);
                $value = $template->render($vars);
            } catch (\Exception $e) {
                if (!$try) {
                    throw $e;
                }
            }
        }

        return $value;
    }
}
