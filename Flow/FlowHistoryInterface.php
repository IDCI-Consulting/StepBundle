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
     * @param StepInterface $step the step
     *
     * @return FlowHistoryInterface
     */
    public function setCurrentStep(StepInterface $step);

    /**
     * Add a taken path.
     *
     * @param StepInterface $step   the step
     * @param int           $pathId the identifier of the path
     */
    public function addTakenPath(StepInterface $step, $pathId = 0);

    /**
     * Retrace to a step.
     *
     * @param string        $sourceStepName  the source step name
     * @param StepInterface $destinationStep the destination step
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
     * @return array the taken paths
     */
    public function getTakenPaths();

    /**
     * Get the full taken paths.
     *
     * @return array the full taken paths
     */
    public function getFullTakenPaths();

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
     * Get all the history in an array.
     *
     * @return array the history
     */
    public function getAll();
}
