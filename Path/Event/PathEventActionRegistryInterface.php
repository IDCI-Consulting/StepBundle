<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event;

use IDCI\Bundle\StepBundle\Path\Event\Action\PathEventActionInterface;

interface PathEventActionRegistryInterface
{
    /**
     * Sets a path event action identify by alias.
     */
    public function setAction(string $alias, PathEventActionInterface $action): self;

    /**
     * Returns a path event action by alias.
     */
    public function getAction(string $alias): PathEventActionInterface;

    /**
     * Returns whether the given path event action is supported.
     */
    public function hasAction(string $alias): bool;
}
