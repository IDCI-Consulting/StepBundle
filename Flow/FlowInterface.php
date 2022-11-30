<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Step\StepInterface;

interface FlowInterface
{
    /**
     * Set the current flow step.
     */
    public function setCurrentStep(StepInterface $step): self;

    /**
     * Returns the current flow step name.
     */
    public function getCurrentStepName(): ?string;

    /**
     * Returns the previous flow step if exists.
     */
    public function getPreviousStepName(): ?string;

    /**
     * Returns the flow navigation history.
     */
    public function getHistory(): FlowHistoryInterface;

    /**
     * Set the flow navigation history.
     */
    public function setHistory(FlowHistoryInterface $history): self;

    /**
     * Get the taken paths.
     */
    public function getTakenPaths(): array;

    /**
     * Whether or not a step has been done.
     *
     * @param StepInterface $step the step
     * @param bool          $full whether or not checking in the full history
     *
     * @return bool true if the step has been done, false otherwise
     */
    public function hasDoneStep(StepInterface $step, $full = false): bool;

    /**
     * Returns the flow navigation data.
     */
    public function getData(): FlowDataInterface;

    /**
     * Set the flow navigation data.
     */
    public function setData(FlowDataInterface $data): self;

    /**
     * Whether or not it exists a data for a step.
     *
     * @param StepInterface $step the step
     * @param string|null   $type the data type (null, 'reminded' or 'retrieved')
     *
     * @return bool true if a data exists for the step, false otherwise
     */
    public function hasStepData(StepInterface $step, string $type = null): bool;

    /**
     * Returns the flow navigation data for a given step.
     *
     * @param StepInterface $step the step
     * @param string|null   $type the data type (null, 'reminded' or 'retrieved')
     */
    public function getStepData(StepInterface $step, string $type = null): array;

    /**
     * Set the flow navigation data for a given step.
     *
     * @param StepInterface $step the step
     * @param array         $data the data to store
     * @param string|null   $type the data type (null, 'reminded' or 'retrieved')
     */
    public function setStepData(StepInterface $step, array $data, string $type = null): self;

    /**
     * Retrace the flow to a step.
     *
     * @param StepInterface $step the target step
     *
     * @return array The retraced paths
     */
    public function retraceTo(StepInterface $step): array;

    /**
     * Take a path.
     */
    public function takePath(PathInterface $path, int $index);
}
