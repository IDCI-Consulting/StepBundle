<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\Type\PathTypeInterface;
use IDCI\Bundle\StepBundle\Step\StepInterface;

interface PathInterface
{
    /**
     * Set the configuration options.
     */
    public function setOptions(array $options): self;

    /**
     * Get the configuration options.
     */
    public function getOptions(): array;

    /**
     * Set the source step.
     */
    public function setSource(StepInterface $step): self;

    /**
     * Get the source step.
     */
    public function getSource(): StepInterface;

    /**
     * Add a destination step.
     */
    public function addDestination(StepInterface $step): self;

    /**
     * Get the destination steps.
     */
    public function getDestinations(): array;

    /**
     * Has the destination step.
     */
    public function hasDestination(string $name): bool;

    /**
     * Get the destination step.
     */
    public function getDestination(string $name): ?StepInterface;

    /**
     * Resolve the destination step.
     */
    public function resolveDestination(NavigatorInterface $navigator): ?StepInterface;

    /**
     * Returns the path types used to construct the path.
     */
    public function getType(): PathTypeInterface;
}
