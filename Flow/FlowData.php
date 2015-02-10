<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

class FlowData implements FlowDataInterface
{
    const TYPE_REMINDED = 'reminded';
    const TYPE_RETRIVED = 'retrieved';

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
     * The retrieved data indexed by steps.
     *
     * @var array
     */
    private $retrievedData = array();

    /**
     * Constructor
     *
     * @param array $data           The steps data.
     * @param array $remindedData   The reminded steps data.
     * @param array $retrievedData  The retrieved steps data.
     */
    public function __construct(
        array $data          = array(),
        array $remindedData  = array(),
        array $retrievedData = array()
    )
    {
        $this->data          = $data;
        $this->remindedData  = $remindedData;
        $this->retrievedData = $retrievedData;
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
    public function getRemindedData()
    {
        return $this->remindedData;
    }

    /**
     * {@inheritdoc}
     */
    public function getRetrievedData()
    {
        return $this->retrievedData;
    }

    /**
     * {@inheritdoc}
     */
    public function hasStepData($name, $type = null)
    {
        if (null === $type) {
            return isset($this->data[$name]);
        }

        if (self::TYPE_REMINDED === $type) {
            return isset($this->remindedData[$name]);
        }

        if (self::TYPE_RETRIVED === $type) {
            return isset($this->retrievedData[$name]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStepData($name, $type = null)
    {
        if (!$this->hasStepData($name, $type)) {
            throw new \LogicException(sprintf(
                'No step "%s" found (%s).',
                $name,
                null === $type ? 'data' : $type
            ));
        }

        if (null === $type) {
            return $this->data[$name];
        }

        if (self::TYPE_REMINDED === $type) {
            return $this->remindedData[$name];
        }

        if (self::TYPE_RETRIVED === $type) {
            return $this->retrievedData[$name];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStepData($name, array $data, $type = null)
    {
        if (null === $type) {
            $this->data[$name] = $data;
        }

        if (self::TYPE_REMINDED === $type) {
            $this->remindedData[$name] = $data;
        }

        if (self::TYPE_RETRIVED === $type) {
            $this->retrievedData[$name] = $data;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function unsetStepData($name, $type = null)
    {
        if (null === $type) {
            unset($this->data[$name]);
        }

        if (self::TYPE_REMINDED === $type) {
            unset($this->remindedData[$name]);
        }

        if (self::TYPE_RETRIVED === $type) {
            unset($this->retrievedData[$name]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $steps = array_merge(
            array_keys($this->data),
            array_keys($this->remindedData),
            array_keys($this->retrievedData)
        );

        $all = array();
        foreach ($steps as $step) {
            $all[$step] = array(
                'data'          => isset($this->data[$step]) ? $this->data[$step] : null,
                'remindedData'  => isset($this->remindedData[$step]) ? $this->remindedData[$step] : null,
                'retrievedData' => isset($this->retrievedData[$step]) ? $this->retrievedData[$step] : null,
            );
        }

        return $all;
    }
}
