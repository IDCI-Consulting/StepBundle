<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step;

use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Step\View\StepView;

class Step implements StepInterface
{
    /**
     * The configuration of the step.
     *
     * @var array
     */
    protected $configuration;

    /**
     * Constructor.
     *
     * @param array $configuration The configuration.
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
    public function getName()
    {
        return $this->configuration['name'];
    }

    /**
     * {@inheritdoc}
     */
    public function createView()
    {
        return new StepView();
    }

    /**
     * {@inheritdoc}
     */
    public function isFirst()
    {
        return $this->configuration['options']['is_first'];
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->configuration['type'];
    }
}
