<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Step\StepInterface;

interface FlowInterface
{
    /**
     * Returns the current flow step
     *
     * @return string
     */
    public function getCurrentStep();

    /**
     * Set the current flow step
     *
     * @param StepInterface $step
     *
     * @return FlowInterface This
     */
    public function setCurrentStep(StepInterface $step);

    /**
     * Returns the previous flow step if exists
     *
     * @return string|null
     */
    public function getPreviousStep();

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
     * @return FlowDataInterface
     *
     * @return FlowInterface This
     */
    public function setData(FlowDataInterface $data);

    /**
     * Returns the flow navigation data for a given step
     *
     * @return array
     */
    public function getStepData(StepInterface $step);

    /*
     retraceTo(step)
     addData(step, data)
     takePath(path, index)
    */
}