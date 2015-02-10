<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

interface FlowDataInterface
{
    /**
     * Returns the data
     *
     * @return array
     */
    public function getData();

    /**
     * Returns the reminded  data
     *
     * @return array
     */
    public function getRemindedData();

    /**
     * Returns the retrieved data
     *
     * @return array
     */
    public function getRetrievedData();

    /**
     * Whether or not it exists a data for a step.
     *
     * @param string      $name  The identifier name of the step.
     * @param string|null $type  The data type (null, 'reminded' or 'retrieved').
     *
     * @return boolean True if it exists a data for the step, false otherwise.
     */
    public function hasStepData($name, $type = null);

    /**
     * Get the data of a step.
     *
     * @param string      $name  The identifier name of the step.
     * @param string|null $type  The data type (null, 'reminded' or 'retrieved').
     *
     * @return array The associated data.
     *
     * @throws \LogicException if there is no associated data for the step.
     */
    public function getStepData($name, $type = null);

    /**
     * Set the data of a step.
     *
     * @param string      $name  The identifier name of the step.
     * @param array       $data  The associated data.
     * @param string|null $type  The data type (null, 'reminded' or 'retrieved').
     *
     * @return FlowDataInterface
     */
    public function setStepData($name, array $data, $type = null);

    /**
     * Unset the data for a step.
     *
     * @param string      $name  The identifier name of the step.
     * @param string|null $type  The data type (null, 'reminded' or 'retrieved').
     *
     * @return FlowDataInterface
     */
    public function unsetStepData($name, $type = null);

    /**
     * Get the data of all the steps in an array.
     *
     * @return array The data.
     */
    public function getAll();
}
