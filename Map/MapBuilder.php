<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use IDCI\Bundle\StepBundle\Step\StepRegistryInterface;
use IDCI\Bundle\StepBundle\Path\PathRegistryInterface;

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
     * @var StepRegistry
     */
    private $stepRegistry;

    /**
     * @var PathRegistry
     */
    private $pathRegistry;

    /**
     * Creates a new map builder.
     *
     * @param string        $name
     * @param array         $data
     * @param array         $options
     * @param StepRegistry  $stepRegistry
     * @param PathRegistry  $pathRegistry
     */
    public function __construct(
        $name,
        $data = array(),
        $options = array(),
        StepRegistryInterface $stepRegistry,
        PathRegistryInterface $pathRegistry
    )
    {
        $this->name         = (string) $name;
        $this->data         = $data;
        $this->options      = $options;
        $this->stepRegistry = $stepRegistry;
        $this->pathRegistry = $pathRegistry;
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
        //TODO
    }

    /**
     * {@inheritdoc}
     */
    public function addPath($source, $type, array $options = array())
    {
        //TODO
    }

    /**
     * {@inheritdoc}
     */
    public function getMap()
    {
        //TODO
    }
}