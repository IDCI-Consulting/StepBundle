<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

class FlowData implements FlowDataInterface
{
    /**
     * The data of the steps.
     *
     * @var array
     */
    protected $steps = array();

    /**
     * {@inheritdoc}
     */
    public function hasStepData($name)
    {
        return isset($this->steps[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getStepData($name)
    {
        if (!$this->hasStepData($name)) {
            throw new \LogicException(sprintf(
                'No step "%s" found.',
                $name
            ));
        }

        return $this->steps[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function setStepData($name, array $data)
    {
        $this->steps[$name] = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function unsetStepData($name)
    {
        unset($this->steps[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDataArray()
    {
        return $this->steps;
    }
}
