<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 *
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

class PathTypeRegistry implements PathTypeRegistryInterface
{
    /**
     * @var PathTypeInterface[]
     */
    private $types = [];

    public function setType(string $alias, PathTypeInterface $path): PathTypeRegistryInterface
    {
        $this->types[$alias] = $path;

        return $this;
    }

    public function getType(string $alias): PathTypeInterface
    {
        if (!isset($this->types[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load path type "%s". Available types are %s', $alias, implode(', ', array_keys($this->types))));
        }

        return $this->types[$alias];
    }

    public function hasType(string $alias): bool
    {
        if (!isset($this->types[$alias])) {
            return false;
        }

        return true;
    }
}
