<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type\Configuration;

interface PathTypeConfigurationRegistryInterface
{
    /**
     * Set a path type configuration identified by a alias.
     */
    public function setConfiguration(string $alias, PathTypeConfigurationInterface $configuration): PathTypeConfigurationRegistryInterface;

    /**
     * Returns all path types configurations.
     */
    public function getConfigurations(): array;

    /**
     * Returns a path type configuration by its alias.
     */
    public function getConfiguration(string $alias): PathTypeConfigurationInterface;

    /**
     * Returns whether the given path type configuration is supported.
     */
    public function hasConfiguration(string $alias): bool;
}
