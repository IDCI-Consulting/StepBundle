<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Step\StepInterface;

interface MapInterface
{
    /**
     * Returns the name of the map.
     */
    public function getName(): string;

    /**
     * Returns the map footprint.
     */
    public function getFootprint(): string;

    /**
     * Returns the map data.
     */
    public function getData(): array;

    /**
     * Returns the configuration.
     */
    public function getConfiguration(): array;

    /**
     * Add a new step.
     */
    public function addStep(string $name, StepInterface $step): self;

    /**
     * Add a new path.
     */
    public function addPath(string $source, PathInterface $path): self;

    /**
     * Check if a step is registered.
     *
     * @param string $name the identifier name of the step
     *
     * @return bool true if the step is registered, false otherwise
     */
    public function hasStep(string $name): bool;

    /**
     * Returns a step by its name if exists.
     */
    public function getStep(string $name): ?StepInterface;

    /**
     * Returns the steps.
     */
    public function getSteps(): array;

    /**
     * Count the steps.
     */
    public function countSteps(): int;

    /**
     * Returns the first step.
     */
    public function getFirstStep(): StepInterface;

    /**
     * Returns the paths.
     *
     * @param string $source the identifier name of the source step
     */
    public function getPaths(string $source = null): array;

    /**
     * Returns a path.
     */
    public function getPath(string $source, int $index): PathInterface;

    /**
     * Returns the final destination (as URL).
     */
    public function getFinalDestination(): ?string;

    /**
     * Returns the form action.
     */
    public function getFormAction(): ?string;

    /**
     * Returns the display step in url status information (enabled/disabled).
     *
     * @return bool true if enabled false otherwise
     */
    public function isDisplayStepInUrlEnabled(): bool;

    /**
     * Returns the reset flow data on init status information (enabled/disabled).
     *
     * @return bool true if enabled false otherwise
     */
    public function isResetFlowDataOnInitEnabled(): bool;
}
