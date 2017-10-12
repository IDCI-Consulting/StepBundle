<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;

interface MapInterface
{
    /**
     * Returns the name of the map.
     *
     * @return string the name
     */
    public function getName();

    /**
     * Returns the map footprint.
     *
     * @return string the map footprint
     */
    public function getFootprint();

    /**
     * Returns the map data.
     *
     * @return array the map data
     */
    public function getData();

    /**
     * Returns the configuration.
     *
     * @return array the configuration
     */
    public function getConfiguration();

    /**
     * Add a new step.
     *
     * @param string        $name the identifier name of the step
     * @param StepInterface $step the step
     *
     * @return MapInterface this
     */
    public function addStep($name, StepInterface $step);

    /**
     * Add a new path.
     *
     * @param string        $source the identifier name of the source step
     * @param PathInterface $path   the path
     *
     * @return MapInterface this
     */
    public function addPath($source, PathInterface $path);

    /**
     * Check if a step is registered.
     *
     * @param string $name the identifier name of the step
     *
     * @return bool true if the step is registered, false otherwise
     */
    public function hasStep($name);

    /**
     * Returns a step by its name if exists.
     *
     * @param string $name the identifier name of the step
     *
     * @return StepInterface|null the step
     */
    public function getStep($name);

    /**
     * Returns the steps.
     *
     * @return array the steps
     */
    public function getSteps();

    /**
     * Count the steps.
     *
     * @return int The step count
     */
    public function countSteps();

    /**
     * Returns the first step.
     *
     * @return StepInterface the step
     */
    public function getFirstStep();

    /**
     * Returns the paths.
     *
     * @param string $source the identifier name of the source step
     *
     * @return array the paths
     */
    public function getPaths($source = null);

    /**
     * Returns a path.
     *
     * @param string $source the identifier name of the source step
     * @param int    $index  the path index for the step
     *
     * @return PathInterface the path
     */
    public function getPath($source, $index);

    /**
     * Returns the final destination (as URL).
     *
     * @return string|null
     */
    public function getFinalDestination();

    /**
     * Returns the form action.
     *
     * @return string|null
     */
    public function getFormAction();

    /**
     * Returns the display step in url status information (enabled/disabled).
     *
     * @return bool true if enabled false otherwise
     */
    public function isDisplayStepInUrlEnabled();

    /**
     * Returns the reset flow data on init status information (enabled/disabled).
     *
     * @return bool true if enabled false otherwise
     */
    public function isResetFlowDataOnInitEnabled();
}
