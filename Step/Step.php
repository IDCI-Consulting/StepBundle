<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step;

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
     * @param array $configuration the configuration
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
    public function getName()
    {
        return $this->configuration['name'];
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

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return isset($this->configuration['options']['data']) ?
            $this->configuration['options']['data'] :
            null
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreStepContent()
    {
        return isset($this->configuration['options']['pre_step_content']) ?
            $this->configuration['options']['pre_step_content'] :
            null
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataTypeMapping()
    {
        return $this->getType()->getDataTypeMapping($this->configuration['options']);
    }
}
