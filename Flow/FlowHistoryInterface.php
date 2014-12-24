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
     * @param string $step The identifier name of the step.
     */
    public function addDoneStep($step);

    /**
     * Whether or not a step has been done.
     *
     * @param string $step The identifier name of the step.
     *
     * @return boolean True if the step has been done, false otherwise.
     */
    public function hasDoneStep($step);

    /**
     * Retrace to a done step.
     *
     * @param string $step The identifier name of the step.
     *
     * @return array The removed done steps.
     */
    public function retraceDoneStep($step);

    /**
     * Whether or not a step has been done then canceled.
     *
     * @param string $step The identifier name of the step.
     *
     * @return boolean True if the step has been done, false otherwise.
     */
    public function hasCanceledStep($step);

    /**
     * Add a taken path.
     *
     * @param string $path The identifier name of the path.
     */
    public function addTakenPath($path);

    /**
     * Whether or not a path has been taken.
     *
     * @param string $path The identifier name of the path.
     *
     * @return boolean True if the step has been done, false otherwise.
     */
    public function hasTakenPath($path);

    /**
     * Retrace to a taken path.
     *
     * @param string $path The identifier name of the path.
     */
    public function retraceTakenPath($path);
}
