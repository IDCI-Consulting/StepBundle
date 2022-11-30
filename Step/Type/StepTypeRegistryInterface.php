<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

interface StepTypeRegistryInterface
{
    /**
     * Sets a step type identify by an alias.
     */
    public function setType(string $alias, StepTypeInterface $step): self;

    /**
     * Returns a step type by an alias.
     */
    public function getType(string $alias): StepTypeInterface;

    /**
     * Returns whether the given step type is supported.
     */
    public function hasType(string $alias): bool;
}
