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
     */
    public function setCurrentStep(StepInterface $step): self;

    /**
     * Add a taken path.
     *
     * @param StepInterface $step   the step
     * @param int           $pathId the identifier of the path
     */
    public function addTakenPath(StepInterface $step, int $pathId = 0);

    /**
     * Retrace to a step.
     *
     * @param string        $sourceStepName  the source step name
     * @param StepInterface $destinationStep the destination step
     */
    public function retraceTakenPath(string $sourceStepName, StepInterface $destinationStep);

    /**
     * Get the last taken path.
     *
     * @return array|null The last taken path under the form array('source' => ..., 'index' => ...).
     */
    public function getLastTakenPath(): ?array;

    /**
     * Get the taken paths.
     */
    public function getTakenPaths(): array;

    /**
     * Get the full taken paths.
     */
    public function getFullTakenPaths(): array;

    /**
     * Whether or not a step has been done.
     *
     * @param StepInterface $step the step
     * @param bool          $full whether or not checking in the full history
     *
     * @return bool true if the step has been done, false otherwise
     */
    public function hasDoneStep(StepInterface $step, bool $full = false): bool;

    /**
     * Get all the history in an array.
     *
     * @return array the history
     */
    public function getAll(): array;
}
