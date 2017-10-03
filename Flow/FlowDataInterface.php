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
     *
     * @return array
     */
    public function getData();

    /**
     * Set data.
     *
     * @param array $data the data
     *
     * @return FlowDataInterface
     */
    public function setData(array $data);

    /**
     * Returns the reminded  data.
     *
     * @return array
     */
    public function getRemindedData();

    /**
     * Set reminded data.
     *
     * @param array $remindedData the reminded data
     *
     * @return FlowDataInterface
     */
    public function setRemindedData(array $remindedData);

    /**
     * Returns the retrieved data.
     *
     * @return array
     */
    public function getRetrievedData();

    /**
     * Set retrieved data.
     *
     * @param array $retrievedData the retrieved data
     *
     * @return FlowDataInterface
     */
    public function setRetrievedData(array $retrievedData);

    /**
     * Whether or not it exists a data for a step.
     *
     * @param string      $name the identifier name of the step
     * @param string|null $type the data type (null, 'reminded' or 'retrieved')
     *
     * @return bool true if a data exists for the step, false otherwise
     */
    public function hasStepData($name, $type = null);

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
    public function getStepData($name, $type = null);

    /**
     * Set the data of a step.
     *
     * @param string      $name the identifier name of the step
     * @param array       $data the associated data
     * @param string|null $type the data type (null, 'reminded' or 'retrieved')
     *
     * @return FlowDataInterface
     */
    public function setStepData($name, array $data, $type = null);

    /**
     * Unset the data for a step.
     *
     * @param string      $name the identifier name of the step
     * @param string|null $type the data type (null, 'reminded' or 'retrieved')
     *
     * @return FlowDataInterface
     */
    public function unsetStepData($name, $type = null);

    /**
     * Returns all data steps in an array.
     *
     * @return array the data
     */
    public function getAll();
}
