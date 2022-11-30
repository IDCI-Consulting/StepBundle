<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event;

use IDCI\Bundle\StepBundle\Step\Event\Action\StepEventActionInterface;

interface StepEventActionRegistryInterface
{
    /**
     * Sets a step event action identify by alias.
     */
    public function setAction(string $alias, StepEventActionInterface $action): self;

    /**
     * Returns a step event action by alias.
     */
    public function getAction(string $alias): StepEventActionInterface;

    /**
     * Returns whether the given step event action is supported.
     */
    public function hasAction(string $alias): bool;
}
