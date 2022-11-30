<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Configuration;

interface StepEventActionConfigurationRegistryInterface
{
    /**
     * Set a step event action configuration identified by a alias.
     */
    public function setConfiguration(string $alias, StepEventActionConfigurationInterface $configuration): self;

    /**
     * Returns all step event actions configurations.
     */
    public function getConfigurations(): array;

    /**
     * Returns a step event action configuration by its alias.
     */
    public function getConfiguration(string $alias): StepEventActionConfigurationInterface;

    /**
     * Returns whether the given step event action configuration is supported.
     */
    public function hasConfiguration(string $alias): bool;
}
