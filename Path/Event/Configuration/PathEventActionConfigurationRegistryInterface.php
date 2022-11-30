<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Configuration;

interface PathEventActionConfigurationRegistryInterface
{
    /**
     * Set a path event action configuration identified by a alias.
     */
    public function setConfiguration(string $alias, PathEventActionConfigurationInterface $configuration): self;

    /**
     * Returns all path event actions configurations.
     */
    public function getConfigurations(): array;

    /**
     * Returns a path event action configuration by its alias.
     */
    public function getConfiguration(string $alias): PathEventActionConfigurationInterface;

    /**
     * Returns whether the given path event action configuration is supported.
     */
    public function hasConfiguration(string $alias): bool;
}
