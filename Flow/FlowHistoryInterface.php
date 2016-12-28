<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Step\StepInterface;

interface FlowHistoryInterface
{
    /**
     * Store the current step into the history.
     *
     * @param StepInterface $step The step.
     *
     * @return FlowHistoryInterface
     */
    public function setCurrentStep(StepInterface $step);

    /**
     * Add a taken path.
     *
     * @param StepInterface $step   The step.
     * @param integer       $pathId The identifier of the path.
     */
    public function addTakenPath(StepInterface $step, $pathId = 0);

    /**
     * Retrace to a step.
     *
     * @param string        $sourceStepName  The source step name.
     * @param StepInterface $destinationStep The destination step.
     */
    public function retraceTakenPath($sourceStepName, StepInterface $destinationStep);

    /**
     * Get the last taken path.
     *
     * @return array|null The last taken path under the form array('source' => ..., 'index' => ...).
     */
    public function getLastTakenPath();

    /**
     * Get the taken paths.
     *
     * @return array The taken paths.
     */
    public function getTakenPaths();

    /**
     * Get the full taken paths.
     *
     * @return array The full taken paths.
     */
    public function getFullTakenPaths();

    /**
     * Whether or not a step has been done.
     *
     * @param StepInterface $step The step.
     * @param boolean       $full Whether or not checking in the full history.
     *
     * @return boolean True if the step has been done, false otherwise.
     */
    public function hasDoneStep(StepInterface $step, $full = false);

    /**
     * Get all the history in an array.
     *
     * @return array The history.
     */
    public function getAll();
}
