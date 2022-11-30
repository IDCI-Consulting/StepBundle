<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

class FlowData implements FlowDataInterface
{
    public const TYPE_REMINDED = 'reminded';
    public const TYPE_RETRIEVED = 'retrieved';

    /**
     * The data indexed by steps.
     *
     * @var array
     */
    protected $data;

    /**
     * The reminded data indexed by steps.
     *
     * @var array
     */
    protected $remindedData;

    /**
     * The retrieved data indexed by steps.
     *
     * @var array
     */
    protected $retrievedData;

    /**
     * Constructor.
     *
     * @param array $data          the steps data
     * @param array $remindedData  the reminded steps data
     * @param array $retrievedData The retrieved steps data
     */
    public function __construct(
        array $data = [],
        array $remindedData = [],
        array $retrievedData = []
    ) {
        $this->data = $data;
        $this->remindedData = $remindedData;
        $this->retrievedData = $retrievedData;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data): FlowDataInterface
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRemindedData(): array
    {
        return $this->remindedData;
    }

    /**
     * {@inheritdoc}
     */
    public function setRemindedData(array $remindedData): FlowDataInterface
    {
        $this->remindedData = $remindedData;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRetrievedData(): array
    {
        return $this->retrievedData;
    }

    /**
     * {@inheritdoc}
     */
    public function setRetrievedData(array $retrievedData): FlowDataInterface
    {
        $this->retrievedData = $retrievedData;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasStepData($name, $type = null): bool
    {
        if (null === $type) {
            return isset($this->data[$name]);
        }

        if (self::TYPE_REMINDED === $type) {
            return isset($this->remindedData[$name]);
        }

        if (self::TYPE_RETRIEVED === $type) {
            return isset($this->retrievedData[$name]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStepData(string $name, string $type = null): array
    {
        if (!$this->hasStepData($name, $type)) {
            throw new \InvalidArgumentException(sprintf('No step "%s" found (%s).', $name, null === $type ? 'data' : $type));
        }

        if (null === $type) {
            return $this->data[$name];
        }

        if (self::TYPE_REMINDED === $type) {
            return $this->remindedData[$name];
        }

        if (self::TYPE_RETRIEVED === $type) {
            return $this->retrievedData[$name];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStepData(string $name, array $data, string $type = null): FlowDataInterface
    {
        if (null === $type) {
            $this->data[$name] = $data;
        }

        if (self::TYPE_REMINDED === $type) {
            $this->remindedData[$name] = $data;
        }

        if (self::TYPE_RETRIEVED === $type) {
            $this->retrievedData[$name] = $data;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function unsetStepData(string $name, string $type = null): FlowDataInterface
    {
        if (null === $type) {
            unset($this->data[$name]);
        }

        if (self::TYPE_REMINDED === $type) {
            unset($this->remindedData[$name]);
        }

        if (self::TYPE_RETRIEVED === $type) {
            unset($this->retrievedData[$name]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        $steps = array_merge(
            array_keys($this->data),
            array_keys($this->remindedData),
            array_keys($this->retrievedData)
        );

        $all = [];
        foreach ($steps as $step) {
            $all[$step] = [
                'data' => isset($this->data[$step]) ? $this->data[$step] : null,
                'remindedData' => isset($this->remindedData[$step]) ? $this->remindedData[$step] : null,
                'retrievedData' => isset($this->retrievedData[$step]) ? $this->retrievedData[$step] : null,
            ];
        }

        return $all;
    }
}
