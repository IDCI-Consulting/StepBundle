<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

interface PathInterface
{
    /**
     * Get the configuration.
     *
     * @return array The configuration.
     */
    public function getConfiguration();

    /**
     * Set the source step.
     *
     * @param StepInterface $step The source step.
     *
     * @return PathInterface This.
     */
    public function setSource(StepInterface $step);

    /**
     * Get the source step.
     *
     * @return StepInterface The source step.
     */
    public function getSource();

    /**
     * Add a destination step.
     *
     * @param StepInterface $step The step.
     *
     * @return PathInterface This.
     */
    public function addDestination(StepInterface $step);

    /**
     * Get the destination steps.
     *
     * @return array The steps.
     */
    public function getDestinations();

    /**
     * Has the destination step.
     *
     * @param string $name A step name to test.
     *
     * @return boolean True if the step has been defined as one of the path destinations.
     */
    public function hasDestination($name);

    /**
     * Get the destination step.
     *
     * @param string $name The destination step name to retrieve.
     *
     * @return StepInterface|null The destination step if exists.
     */
    public function getDestination($name);

    /**
     * Resolve the destination step.
     *
     * @param NavigatorInterface $navigator.
     *
     * @return StepInterface|null The resolved destination step if exists.
     */
    public function resolveDestination(NavigatorInterface $navigator);

    /**
     * Returns the path types used to construct the path.
     *
     * @return PathTypeInterface The path's type.
     */
    public function getType();
}
