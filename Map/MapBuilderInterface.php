<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use Symfony\Component\HttpFoundation\Request;

interface MapBuilderInterface
{
    /**
     * Get the map name.
     *
     * @return string The map name.
     */
    public function getName();

    /**
     * Get the map data.
     *
     * @return array The map data.
     */
    public function getData();

    /**
     * Get the map options.
     *
     * @return array The map options.
     */
    public function getOptions();

    /**
     * Checks if the map contains the given options name.
     *
     * @param string $name The searching option name.
     *
     * @return boolean
     */
    public function hasOption($name);

    /**
     * Retrieve the given options name.
     *
     * @param string $name      The searching option name.
     * @param mixed  $default   The default return value if the searching option not found.
     *
     * @return mixed
     */
    public function getOption($name, $default = null);

    /**
     * Adds a new step to this map. A step must have a unique name within
     * the map. Otherwise the existing step is overwritten.
     *
     * @param string    $name
     * @param string    $type
     * @param array     $options
     *
     * @return MapBuilderInterface The builder object.
     */
    public function addStep($name, $type, array $options = array());

    /**
     * Adds a new path to this map.
     *
     * @param string    $type
     * @param array     $options
     *
     * @return MapBuilderInterface The builder object.
     */
    public function addPath($type, array $options = array());

    /**
     * Get the building map.
     *
     * @param Request $request The HTTP request.
     *
     * @return MapInterface The map.
     */
    public function getMap(Request $request);
}
