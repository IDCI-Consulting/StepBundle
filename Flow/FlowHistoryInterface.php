<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

interface FlowHistoryInterface
{
    /**
     * Add a done step.
     *
     * @param string $name The identifier name of the step.
     */
    public function addDoneStep($name);

    /**
     * Whether or not a step has been done.
     *
     * @param string $name The identifier name of the step.
     *
     * @return boolean True if the step has been done, false otherwise.
     */
    public function hasDoneStep($name);

    /**
     * Add a done step.
     *
     * @param string $name The identifier name of the step.
     */
    public function addTakenPath($name);

    /**
     * Whether or not a step has been done.
     *
     * @param string $name The identifier name of the step.
     *
     * @return boolean True if the step has been done, false otherwise.
     */
    public function hasTakenPath($name);

    /**
     * Retrace to a done step.
     *
     * @param string $name The identifier name of the step.
     *
     * @return array The removed done steps.
     */
    public function retraceDoneStep($name);
}
