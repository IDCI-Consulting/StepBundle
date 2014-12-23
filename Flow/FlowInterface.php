<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

interface FlowInterface
{
    /**
     * Returns the current flow step
     *
     * @return string
     */
    public function getCurrentStep();

    /**
     * Returns the flow navigation history
     *
     * @return FlowHistoryInterface
     */
    public function getHistory();

    /**
     * Returns the flow navigation data
     *
     * @return FlowDataInterface
     */
    public function getData();
}