<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

class FlowData implements FlowDataInterface
{
    /**
     * The data indexed by steps.
     *
     * @var array
     */
    protected $data = array();

    /**
     * The reminded data indexed by steps.
     *
     * @var array
     */
    private $remindedData;

    /**
     * Constructor
     *
     * @param array $steps The steps data.
     */
    public function __construct($steps = array())
    {
        $this->steps = $steps;
    }

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
    public function getAll()
    {
        return $this->steps;
    }
}
