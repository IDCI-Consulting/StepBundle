<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Configuration;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

interface StepEventActionConfigurationRegistryInterface
{
    /**
     * Set a step event action configuration identified by a alias.
     *
     * @param string                                $alias The step event action configuration alias.
     * @param StepEventActionConfigurationInterface $configuration The step event action configuration
     *
     * @return StepEventActionConfigurationRegistryInterface
     */
    public function setConfiguration($alias, StepEventActionConfigurationInterface $configuration);

    /**
     * Returns all step event actions configurations.
     *
     * @return array
     */
    public function getConfigurations();

    /**
     * Returns a step event action configuration by its alias.
     *
     * @param string $alias The step event action configuration alias.
     *
     * @return StepEventActionConfigurationInterface
     *
     * @throws UnexpectedTypeException if the passed alias is not a string.
     * @throws \InvalidArgumentException if the step event action configuration can not be retrieved.
     */
    public function getConfiguration($alias);

    /**
     * Returns whether the given step event action configuration is supported.
     *
     * @param string $alias The alias of the step event action configuration.
     *
     * @return bool Whether the step event action configuration is supported.
     */
    public function hasConfiguration($alias);
}
