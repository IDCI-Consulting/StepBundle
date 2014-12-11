<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

use IDCI\Bundle\StepBundle\Step\StepInterface;

interface PathInterface
{
    /**
     * Set the source step.
     *
     * @param StepInterface $step The step.
     *
     * @return PathInterface This.
     */
    public function setSource(StepInterface $step);

    /**
     * Add a destination step.
     *
     * @param StepInterface $step The step.
     *
     * @return PathInterface This.
     */
    public function addDestination(StepInterface $step);

    /**
     * Get the type of the path.
     *
     * @return string The type.
     */
    public function getType();

    /**
     * Get the options.
     *
     * @return array The options.
     */
    public function getOptions();

    /**
     * Get the source step.
     *
     * @return StepInterface The source step.
     */
    public function getSource();

    /**
     * Get the destination steps.
     *
     * @return array The steps.
     */
    public function getDestinations();
}
