<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type\Configuration;

interface StepTypeConfigurationRegistryInterface
{
    /**
     * Set a step type configuration identified by a alias.
     */
    public function setConfiguration(string $alias, StepTypeConfigurationInterface $configuration): self;

    /**
     * Returns all step types configurations.
     */
    public function getConfigurations(): array;

    /**
     * Returns a step type configuration by its alias.
     */
    public function getConfiguration(string $alias): StepTypeConfigurationInterface;

    /**
     * Returns whether the given step type configuration is supported.
     */
    public function hasConfiguration(string $alias): bool;
}
