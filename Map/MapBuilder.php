<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use Symfony\Component\OptionsResolver\OptionsResolver;
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
    private $steps = array();

    /**
     * @var array
     */
    private $paths = array();

    /**
     * @var StepBuilderInterface
     */
    private $stepBuilder;

    /**
     * @var PathBuilderInterface
     */
    private $pathBuilder;

    /**
     * @var MapNavigatorInterface
     */
    private $mapNavigator;

    /**
     * @var MapInterface
     */
    private $builtMap;

    /**
     * Creates a new map builder.
     *
     * @param string                $name
     * @param array                 $data
     * @param array                 $options
     * @param StepBuilderInterface  $stepBuilder
     * @param PathBuilderInterface  $pathBuilder
     */
    public function __construct(
        $name,
        $data = array(),
        $options = array(),
        StepBuilderInterface $stepBuilder,
        PathBuilderInterface $pathBuilder,
        MapNavigatorInterface $mapNavigator
    )
    {
        $this->name         = (string) $name;
        $this->data         = $data;
        $this->options      = self::resolveOptions($options);
        $this->stepBuilder  = $stepBuilder;
        $this->pathBuilder  = $pathBuilder;
        $this->mapNavigator = $mapNavigator;
        $this->steps        = array();
        $this->paths        = array();
        $this->builtMap     = null;
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
                'browsing'      => 'linear',
                'flowDataStore' => 'session',
            ))
            ->setAllowedValues(array(
                'browsing' => array('linear', 'free')
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
        $this->initMap();
        $this->buildSteps();
        $this->buildPaths();

        return $this->builtMap;
    }

    /**
     * Init the map.
     */
    private function initMap()
    {
        // TODO: Use a MapConfig as argument instead of an array.
        $this->builtMap = new Map(
            $this->name,
            $this->mapNavigator,
            array(
                'data'      => $this->data,
                'options'   => $this->options
            )
        );
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
                $parameters['options'],
                $this->builtMap
            );

            if (null !== $step) {
                $this->builtMap->addStep($name, $step);
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
                $parameters['options'],
                $this->builtMap
            );

            if (null !== $path) {
                $this->builtMap->addPath(
                    $path->getSource()->getName(),
                    $path
                );
            }
        }
    }
}