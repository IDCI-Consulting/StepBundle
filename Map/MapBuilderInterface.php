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
     */
    public function getName(): string;

    /**
     * Get the map data.
     */
    public function getData(): array;

    /**
     * Get the map options.
     */
    public function getOptions(): array;

    /**
     * Checks if the map contains the given options name.
     */
    public function hasOption(string $name): bool;

    /**
     * Retrieve the given options name.
     *
     * @param string $name    the searching option name
     * @param mixed  $default the default return value if the searching option not found
     *
     * @return mixed
     */
    public function getOption(string $name, $default = null);

    /**
     * Adds a new step to this map. A step must have a unique name within
     * the map. Otherwise the existing step is overwritten.
     */
    public function addStep(string $name, string $type, array $options = []): self;

    /**
     * Adds a new path to this map.
     */
    public function addPath(string $type, array $options = []): self;

    /**
     * Get the building map.
     */
    public function getMap(Request $request): MapInterface;
}
