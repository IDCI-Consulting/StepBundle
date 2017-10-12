<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;

interface FlowInterface
{
    /**
     * Set the current flow step.
     *
     * @param StepInterface $step
     *
     * @return FlowInterface This
     */
    public function setCurrentStep(StepInterface $step);

    /**
     * Returns the current flow step name.
     *
     * @return string|null
     */
    public function getCurrentStepName();

    /**
     * Returns the previous flow step if exists.
     *
     * @return string|null
     */
    public function getPreviousStepName();

    /**
     * Returns the flow navigation history.
     *
     * @return FlowHistoryInterface
     */
    public function getHistory();

    /**
     * Set the flow navigation history.
     *
     * @param FlowHistoryInterface $history
     *
     * @return FlowInterface This
     */
    public function setHistory(FlowHistoryInterface $history);

    /**
     * Get the taken paths.
     *
     * @return array the taken paths
     */
    public function getTakenPaths();

    /**
     * Whether or not a step has been done.
     *
     * @param StepInterface $step the step
     * @param bool          $full whether or not checking in the full history
     *
     * @return bool true if the step has been done, false otherwise
     */
    public function hasDoneStep(StepInterface $step, $full = false);

    /**
     * Returns the flow navigation data.
     *
     * @return FlowDataInterface
     */
    public function getData();

    /**
     * Set the flow navigation data.
     *
     * @param FlowDataInterface
     *
     * @return FlowInterface This
     */
    public function setData(FlowDataInterface $data);

    /**
     * Whether or not it exists a data for a step.
     *
     * @param StepInterface $step the step
     * @param string|null   $type the data type (null, 'reminded' or 'retrieved')
     *
     * @return bool true if a data exists for the step, false otherwise
     */
    public function hasStepData(StepInterface $step, $type = null);

    /**
     * Returns the flow navigation data for a given step.
     *
     * @param StepInterface $step the step
     * @param string|null   $type the data type (null, 'reminded' or 'retrieved')
     *
     * @return array
     */
    public function getStepData(StepInterface $step, $type = null);

    /**
     * Set the flow navigation data for a given step.
     *
     * @param StepInterface $step the step
     * @param array         $data the data to store
     * @param string|null   $type the data type (null, 'reminded' or 'retrieved')
     *
     * @return FlowInterface This
     */
    public function setStepData(StepInterface $step, array $data, $type = null);

    /**
     * Retrace the flow to a step.
     *
     * @param StepInterface $step the target step
     *
     * @return array The retraced paths
     */
    public function retraceTo(StepInterface $step);

    /**
     * Take a path.
     *
     * @param PathInterface $path
     * @param int           $index
     */
    public function takePath(PathInterface $path, $index);
}
