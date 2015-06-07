<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class Path implements PathInterface
{
    /**
     * The configuration.
     *
     * @var array
     */
    protected $configuration;

    /**
     * The source step.
     *
     * @var StepInterface
     */
    protected $source;

    /**
     * The destinations step.
     *
     * @var array
     */
    protected $destinations = array();

    /**
     * Constructor.
     *
     * @param array $configuration  The configuration.
     */
    public function __construct(array $configuration = array())
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions($options)
    {
        $this->configuration['options'] = $options;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->configuration['options'];
    }

    /**
     * {@inheritdoc}
     */
    public function setSource(StepInterface $step)
    {
        $this->source = $step;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function addDestination(StepInterface $step)
    {
        $this->destinations[$step->getName()] = $step;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDestinations()
    {
        return $this->destinations;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDestination($name)
    {
        return isset($this->destinations[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDestination($name)
    {
        return $this->hasDestination($name) ?
            $this->destinations[$name] :
            null
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveDestination(NavigatorInterface $navigator)
    {
        $destinationName = $this
            ->getType()
            ->resolveDestination($this->configuration['options'], $navigator)
        ;

        return $this->getDestination($destinationName);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->configuration['type'];
    }
}
