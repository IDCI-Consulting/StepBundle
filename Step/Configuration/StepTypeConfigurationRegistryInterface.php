<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Configuration;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

interface StepTypeConfigurationRegistryInterface
{
    /**
     * Set a step type configuration identified by a alias.
     *
     * @param string                         $alias The step type configuration alias.
     * @param StepTypeConfigurationInterface $configuration The step type configuration
     *
     * @return StepTypeConfigurationRegistryInterface
     */
    public function setConfiguration($alias, StepTypeConfigurationInterface $configuration);

    /**
     * Returns all step types configurations.
     *
     * @return array
     */
    public function getConfigurations();

    /**
     * Returns a step type configuration by its alias.
     *
     * @param string $alias The step type configuration alias.
     *
     * @return StepTypeConfigurationInterface
     *
     * @throws UnexpectedTypeException if the passed alias is not a string.
     * @throws \InvalidArgumentException if the step type configuration can not be retrieved.
     */
    public function getConfiguration($alias);

    /**
     * Returns whether the given step type configuration is supported.
     *
     * @param string $alias The alias of the step type configuration.
     *
     * @return bool Whether the step type configuration is supported.
     */
    public function hasConfiguration($alias);
}
