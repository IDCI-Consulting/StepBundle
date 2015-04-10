<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use IDCI\Bundle\StepBundle\Step\StepBuilderInterface;
use IDCI\Bundle\StepBundle\Path\PathBuilderInterface;

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
     * @var StepBuilderInterface
     */
    private $stepBuilder;

    /**
     * @var PathBuilderInterface
     */
    private $pathBuilder;

    /**
     * @var \Twig_Environment
     */
    private $merger;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var MapInterface
     */
    private $map;

    /**
     * Creates a new map builder.
     *
     * @param string                   $name            The map name.
     * @param array                    $data            The map data.
     * @param array                    $options         The map options.
     * @param StepBuilderInterface     $stepBuilder     The sdtep builder.
     * @param PathBuilderInterface     $pathBuilder     The path builder.
     * @param Twig_Environment         $merger          The twig merger.
     * @param SecurityContextInterface $securityContext The security context.
     * @param SessionInterface         $session         The session.
     */
    public function __construct(
        $name = null,
        $data = array(),
        $options = array(),
        StepBuilderInterface $stepBuilder,
        PathBuilderInterface $pathBuilder,
        \Twig_Environment $merger,
        SecurityContextInterface $securityContext,
        SessionInterface $session
    )
    {
        $this->name            = $name;
        $this->data            = $data;
        $this->options         = self::resolveOptions($options);
        $this->stepBuilder     = $stepBuilder;
        $this->pathBuilder     = $pathBuilder;
        $this->merger          = $merger;
        $this->securityContext = $securityContext;
        $this->session         = $session;
        $this->steps           = array();
        $this->paths           = array();
        $this->map             = null;
    }

    /**
     * Resolve options
     *
     * @param array $options The options to resolve.
     *
     * @return array The resolved options.
     */
    public static function resolveOptions(array $options = array())
    {
        $resolver = new OptionsResolver();
        $resolver
            ->setDefaults(array(
                'browsing'        => 'linear',
                'data_store'      => 'session',
                'first_step_name' => null,
            ))
            ->setAllowedValues(array(
                'browsing' => array('linear', 'free'),
            ))
        ;

        return $resolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($name, $default = null)
    {
        return $this->hasOption($name) ? $this->options[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function addStep($name, $type, array $options = array())
    {
        $this->steps[$name] = array(
            'type'      => $type,
            'options'   => $options
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addPath($type, array $options = array())
    {
        $this->paths[] = array(
            'type'      => $type,
            'options'   => $options
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMap()
    {
        $this->build();

        return $this->map;
    }

    /**
     * Build the map.
     */
    private function build()
    {
        // TODO: Use a MapConfig as argument instead of an array.
        $this->map = new Map(array(
            'name'         => $this->name,
            'finger_print' => $this->generateFingerPrint(),
            'data'         => $this->data,
            'options'      => $this->options
        ));

        // Build steps before paths !
        $this->buildSteps();
        $this->buildPaths();
    }

    /**
     * Generate a map unique finger print based on its steps and paths.
     *
     * @return string
     */
    private function generateFingerPrint()
    {
        return md5(json_encode($this->steps).json_encode($this->paths));
    }

    /**
     * Build steps into the map.
     */
    private function buildSteps()
    {
        foreach ($this->steps as $name => $parameters) {
            $step = $this->stepBuilder->build(
                $name,
                $parameters['type'],
                $this->merge($parameters['options'])
            );

            if (null !== $step) {
                $this->map->addStep($name, $step);
            }

            if (null === $this->map->getFirstStepName() || $step->isFirst()) {
                $this->map->setFirstStepName($name);
            }
        }
    }

    /**
     * Build paths into the map.
     */
    private function buildPaths()
    {
        foreach ($this->paths as $parameters) {
            $path = $this->pathBuilder->build(
                $parameters['type'],
                $this->merge($parameters['options']),
                $this->map->getSteps()
            );

            if (null !== $path) {
                $this->map->addPath(
                    $path->getSource()->getName(),
                    $path
                );
            }
        }
    }

    /**
     * Merge options with the SecurityContext (user)
     * and the session (session).
     *
     * @param array $options The options.
     *
     * @return array
     */
    protected function merge(array $options = array())
    {
        $user = null;
        if (null !== $this->securityContext->getToken()) {
            $user = $this->securityContext->getToken()->getUser();
        }

        foreach ($options as $k => $v) {
            // Do not merge events parameters and building objects !
            if ($k == 'events' || is_object($v)) {
                continue;
            }

            $options[$k] = json_decode(
                $this->merger->render(
                    json_encode($v, JSON_UNESCAPED_UNICODE),
                    array(
                        'user'    => $user,
                        'session' => $this->session->all(),
                    )
                ),
                true
            );
        }

        return $options;
    }
}