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
    private $remindedData = array();

    /**
     * Constructor
     *
     * @param array $data The steps data.
     */
    public function __construct(
        array $data = array(),
        array $remindedData = array()
    )
    {
        $this->data = $data;
        $this->remindedData = $remindedData;
    }

    /**
     * {@inheritdoc}
     */
    public function hasStepData($name)
    {
        return isset($this->data[$name]);
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

        return $this->data[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function setStepData($name, array $data)
    {
        $this->data[$name] = $data;
        $this->remindedData[$name] = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function unsetStepData($name)
    {
        unset($this->data[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasRemindedStepData($name)
    {
        return isset($this->remindedData[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getRemindedStepData($name)
    {
        if (!$this->hasRemindedStepData($name)) {
            throw new \LogicException(sprintf(
                'No step "%s" found.',
                $name
            ));
        }

        return $this->remindedData[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        return array(
            'data' => $this->data,
            'remindedData' => $this->remindedData
        );
    }
}