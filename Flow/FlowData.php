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
    public function hasStep($name)
    {
        return isset($this->steps[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getStep($name)
    {
        if (!$this->hasStep($name)) {
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
    public function setStep($name, array $data)
    {
        $this->steps[$name] = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function unsetStep($name)
    {
        unset($this->steps[$name]);
    }
}
