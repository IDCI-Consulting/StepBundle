<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

interface FlowDataInterface
{
    /**
     * Whether or not it exists a data for a step.
     *
     * @param string $name The identifier name of the step.
     *
     * @return boolean True if it exists a data for the step, false otherwise.
     */
    public function hasStepData($name);

    /**
     * Get the data of a step.
     *
     * @param string $name The identifier name of the step.
     *
     * @return array The associated data.
     *
     * @throws \LogicException if there is no associated data for the step.
     */
    public function getStepData($name);

    /**
     * Set the data of a step.
     *
     * @param string $name The identifier name of the step.
     * @param array  $data The associated data.
     */
    public function setStepData($name, array $data);

    /**
     * Unset the data for a step.
     *
     * @param string $name The identifier name of the step.
     */
    public function unsetStepData($name);

    /**
     * Get the data of all the steps in an array.
     *
     * @return array The data.
     */
    public function getAll();

    /**
     * Returns the flow navigation reminded data
     *
     * @return FlowDataInterface
     */
    public function getRemindedData();

    /**
     * Set the flow navigation reminded data
     *
     * @return FlowDataInterface
     *
     * @return FlowInterface This
     */
    public function setRemindedData(FlowDataInterface $data);
}
