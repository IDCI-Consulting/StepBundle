<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;

interface MapInterface
{
    /**
     * Add a new step.
     *
     * @param string        $name The identifier name of the step.
     * @param StepInterface $step The step.
     *
     * @return MapInterface This.
     */
    public function addStep($name, StepInterface $step);

    /**
     * Add a new path.
     *
     * @param string        $name The identifier name of the path.
     * @param PathInterface $path The path.
     *
     * @return MapInterface This.
     */
    public function addPath(PathInterface $path);

    /**
     * Check if a step is registered.
     *
     * @param string $name The identifier name of the step.
     *
     * @return boolean True if the step is registered, false otherwise.
     */
    public function hasStep($name);

    /**
     * Check if a path is registered.
     *
     * @param string $name The identifier name of the path.
     *
     * @return boolean True if the path is registered, false otherwise.
     */
    public function hasPath($name);

    /**
     * Get a step.
     *
     * @param string $name The identifier name of the step.
     *
     * @return StepInterface The step.
     */
    public function getStep($name);

    /**
     * Get a path.
     *
     * @param string $name The identifier name of the path.
     *
     * @return PathInterface The path.
     */
    public function getPath($name);

    /**
     * Get a step.
     *
     * @return array The steps.
     */
    public function getSteps();

    /**
     * Get a path.
     *
     * @return array The paths.
     */
    public function getPaths();

    /**
     * Create a view.
     *
     * @return IDCI\Bundle\StepBundle\Map\View\MapView The view.
     */
    public function createView();
}
