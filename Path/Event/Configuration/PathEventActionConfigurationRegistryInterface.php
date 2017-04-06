<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Configuration;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

interface PathEventActionConfigurationRegistryInterface
{
    /**
     * Set a path event action configuration identified by a alias.
     *
     * @param string                                $alias The path event action configuration alias.
     * @param PathEventActionConfigurationInterface $configuration The path event action configuration
     *
     * @return PathEventActionConfigurationRegistryInterface
     */
    public function setConfiguration($alias, PathEventActionConfigurationInterface $configuration);

    /**
     * Returns all path event actions configurations.
     *
     * @return array
     */
    public function getConfigurations();

    /**
     * Returns a path event action configuration by its alias.
     *
     * @param string $alias The path event action configuration alias.
     *
     * @return PathEventActionConfigurationInterface
     *
     * @throws UnexpectedTypeException if the passed alias is not a string.
     * @throws \InvalidArgumentException if the path event action configuration can not be retrieved.
     */
    public function getConfiguration($alias);

    /**
     * Returns whether the given path event action configuration is supported.
     *
     * @param string $alias The alias of the path event action configuration.
     *
     * @return bool Whether the path event action configuration is supported.
     */
    public function hasConfiguration($alias);
}
