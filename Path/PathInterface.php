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
}
