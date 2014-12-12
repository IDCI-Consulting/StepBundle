<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

use IDCI\Bundle\StepBundle\Step\StepInterface;

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
        $this->destinations[] = $step;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDestinations()
    {
        return $this->destinations;
    }
}
