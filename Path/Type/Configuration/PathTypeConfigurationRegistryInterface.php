<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type\Configuration;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

interface PathTypeConfigurationRegistryInterface
{
    /**
     * Set a path type configuration identified by a alias.
     *
     * @param string                         $alias         the path type configuration alias
     * @param PathTypeConfigurationInterface $configuration The path type configuration
     *
     * @return PathTypeConfigurationRegistryInterface
     */
    public function setConfiguration($alias, PathTypeConfigurationInterface $configuration);

    /**
     * Returns all path types configurations.
     *
     * @return array
     */
    public function getConfigurations();

    /**
     * Returns a path type configuration by its alias.
     *
     * @param string $alias the path type configuration alias
     *
     * @return PathTypeConfigurationInterface
     *
     * @throws UnexpectedTypeException   if the passed alias is not a string
     * @throws \InvalidArgumentException if the path type configuration can not be retrieved
     */
    public function getConfiguration($alias);

    /**
     * Returns whether the given path type configuration is supported.
     *
     * @param string $alias the alias of the path type configuration
     *
     * @return bool whether the path type configuration is supported
     */
    public function hasConfiguration($alias);
}
