<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;

interface FlowInterface
{
    /**
     * Set the current flow step
     *
     * @param StepInterface $step
     *
     * @return FlowInterface This
     */
    public function setCurrentStep(StepInterface $step);

    /**
     * Returns the current flow step name
     *
     * @return string|null
     */
    public function getCurrentStepName();

    /**
     * Returns the previous flow step if exists
     *
     * @return string|null
     */
    public function getPreviousStepName();

    /**
     * Returns the flow navigation history
     *
     * @return FlowHistoryInterface
     */
    public function getHistory();

    /**
     * Set the flow navigation history
     *
     * @param FlowHistoryInterface $history
     *
     * @return FlowInterface This
     */
    public function setHistory(FlowHistoryInterface $history);

    /**
     * Returns the flow navigation data
     *
     * @return FlowDataInterface
     */
    public function getData();

    /**
     * Set the flow navigation data
     *
     * @param FlowDataInterface
     *
     * @return FlowInterface This
     */
    public function setData(FlowDataInterface $data);

    /**
     * Returns the flow navigation data for a given step
     *
     * @param StepInterface $step     The step.
     * @param boolean       $reminded Returns the reminded data or not.
     *
     * @return array
     */
    public function getStepData(StepInterface $step, $reminded = false);

    /**
     * Set the flow navigation data for a given step
     *
     * @param FlowDataInterface
     * @param array
     */
    public function setStepData(StepInterface $step, array $data);

    /**
     * Retrace the flow to a step
     *
     * @return array The retraced paths
     */
    public function retraceTo(StepInterface $step);

    /**
     * Take a path
     *
     * @param PathInterface
     * @param integer
     */
    public function takePath(PathInterface $path, $index);
}