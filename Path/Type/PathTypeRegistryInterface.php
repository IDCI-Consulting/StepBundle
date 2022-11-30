<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

interface PathTypeRegistryInterface
{
    /**
     * Sets a path type identify by a alias.
     */
    public function setType(string $alias, PathTypeInterface $path): self;

    /**
     * Returns a path type by alias.
     */
    public function getType(string $alias): PathTypeInterface;

    /**
     * Returns whether the given path type is supported.
     */
    public function hasType(string $alias): bool;
}
