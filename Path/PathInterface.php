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
     * @return array the configuration
     */
    public function getConfiguration();

    /**
     * Set the configuration options.
     *
     * @param array $options the configuration options
     *
     * @return pathInterface
     */
    public function setOptions($options);

    /**
     * Get the configuration options.
     *
     * @return array the configuration options
     */
    public function getOptions();

    /**
     * Set the source step.
     *
     * @param StepInterface $step the source step
     *
     * @return PathInterface this
     */
    public function setSource(StepInterface $step);

    /**
     * Get the source step.
     *
     * @return StepInterface the source step
     */
    public function getSource();

    /**
     * Add a destination step.
     *
     * @param StepInterface $step the step
     *
     * @return PathInterface this
     */
    public function addDestination(StepInterface $step);

    /**
     * Get the destination steps.
     *
     * @return array the steps
     */
    public function getDestinations();

    /**
     * Has the destination step.
     *
     * @param string $name a step name to test
     *
     * @return bool true if the step has been defined as one of the path destinations
     */
    public function hasDestination($name);

    /**
     * Get the destination step.
     *
     * @param string $name the destination step name to retrieve
     *
     * @return StepInterface|null the destination step if exists
     */
    public function getDestination($name);

    /**
     * Resolve the destination step.
     *
     * @param navigatorInterface $navigator
     *
     * @return StepInterface|null the resolved destination step if exists
     */
    public function resolveDestination(NavigatorInterface $navigator);

    /**
     * Returns the path types used to construct the path.
     *
     * @return PathTypeInterface the path's type
     */
    public function getType();
}
