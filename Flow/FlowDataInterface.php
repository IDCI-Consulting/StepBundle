<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

interface FlowDataInterface
{
    /**
     * Returns the data.
     */
    public function getData(): array;

    /**
     * Set data.
     *
     * @param array $data the data
     *
     * @return FlowDataInterface
     */
    public function setData(array $data): self;

    /**
     * Returns the reminded data.
     */
    public function getRemindedData(): array;

    /**
     * Set reminded data.
     */
    public function setRemindedData(array $remindedData): self;

    /**
     * Returns the retrieved data.
     */
    public function getRetrievedData(): array;

    /**
     * Set retrieved data.
     */
    public function setRetrievedData(array $retrievedData): self;

    /**
     * Whether or not it exists a data for a step.
     *
     * @param string      $name the identifier name of the step
     * @param string|null $type the data type (null, 'reminded' or 'retrieved')
     *
     * @return bool true if a data exists for the step, false otherwise
     */
    public function hasStepData(string $name, string $type = null): bool;

    /**
     * Returns the data of a step.
     *
     * @param string      $name the identifier name of the step
     * @param string|null $type the data type (null, 'reminded' or 'retrieved')
     *
     * @return array the associated data
     *
     * @throws \InvalidArgumentException if there is no associated data for the step
     */
    public function getStepData(string $name, string $type = null): array;

    /**
     * Set the data of a step.
     *
     * @param string      $name the identifier name of the step
     * @param array       $data the associated data
     * @param string|null $type the data type (null, 'reminded' or 'retrieved')
     */
    public function setStepData(string $name, array $data, string $type = null): FlowDataInterface;

    /**
     * Unset the data for a step.
     *
     * @param string      $name the identifier name of the step
     * @param string|null $type the data type (null, 'reminded' or 'retrieved')
     */
    public function unsetStepData(string $name, string $type = null): FlowDataInterface;

    /**
     * Returns all data steps in an array.
     */
    public function getAll(): array;
}
