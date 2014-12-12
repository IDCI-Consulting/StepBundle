<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

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
        PathBuilderInterface $pathBuilder
    )
    {
        $this->name        = (string) $name;
        $this->data        = $data;
        $this->options     = $options;
        $this->stepBuilder = $stepBuilder;
        $this->pathBuilder = $pathBuilder;
        $this->steps       = array();
        $this->paths       = array();
        $this->builtMap    = null;
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
        $this->builtMap = new Map();
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