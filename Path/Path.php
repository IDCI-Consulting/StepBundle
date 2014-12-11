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
     * The type of the path.
     *
     * @var string
     */
    protected $type;

    /**
     * The options.
     *
     * @var array
     */
    protected $options;

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
     * @param StepInterface $type    The type of the path.
     * @param array         $options The options.
     */
    public function __construct($type, array $options = array())
    {
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function setSource(StepInterface $step)
    {
        $this->source = $step;
    }

    /**
     * {@inheritdoc}
     */
    public function addDestination(StepInterface $step)
    {
        $this->destinations[] = $step;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
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
    public function getDestinations()
    {
        return $this->destinations;
    }
}
