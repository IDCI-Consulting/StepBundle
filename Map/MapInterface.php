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
     * @return string The name.
     */
    public function getName();

    /**
     * Returns the map footprint.
     *
     * @return string The map footprint.
     */
    public function getFootprint();

    /**
     * Returns the map data.
     *
     * @return array|null The map data.
     */
    public function getData();

    /**
     * Returns the configuration.
     *
     * @return array The configuration.
     */
    public function getConfiguration();

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
     * @param string        $source The identifier name of the source step.
     * @param PathInterface $path   The path.
     *
     * @return MapInterface This.
     */
    public function addPath($source, PathInterface $path);

    /**
     * Check if a step is registered.
     *
     * @param string $name The identifier name of the step.
     *
     * @return boolean True if the step is registered, false otherwise.
     */
    public function hasStep($name);

    /**
     * Returns a step by its name if exists.
     *
     * @param string $name The identifier name of the step.
     *
     * @return StepInterface|null The step.
     */
    public function getStep($name);

    /**
     * Returns the steps.
     *
     * @return array The steps.
     */
    public function getSteps();

    /**
     * Count the steps.
     *
     * @return integer The step count
     */
    public function countSteps();

    /**
     * Returns the first step.
     *
     * @return StepInterface The step.
     */
    public function getFirstStep();

    /**
     * Returns the paths.
     *
     * @param string $source The identifier name of the source step.
     *
     * @return array The paths.
     */
    public function getPaths($source = null);

    /**
     * Returns a path.
     *
     * @param string  $source The identifier name of the source step.
     * @param integer $index  The path index for the step.
     *
     * @return PathInterface The path.
     */
    public function getPath($source, $index);
}
