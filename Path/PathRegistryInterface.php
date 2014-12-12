<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Path;

use IDCI\Bundle\StepBundle\Path\Type\PathTypeInterface;

interface PathRegistryInterface
{
    /**
     * Sets a path type identify by a alias.
     *
     * @param string            $alias  The type alias.
     * @param PathTypeInterface $path   The type.
     *
     * @return PathRegistryInterface
     */
    public function setType($alias, PathTypeInterface $path);

    /**
     * Returns a path type by alias.
     *
     * @param string $alias The alias of the type.
     *
     * @return PathTypeInterface The type
     *
     * @throws Exception\UnexpectedTypeException  if the passed alias is not a string.
     * @throws Exception\InvalidArgumentException if the type can not be retrieved.
     */
    public function getType($alias);

    /**
     * Returns whether the given path type is supported.
     *
     * @param string $alias The alias of the type.
     *
     * @return bool Whether the type is supported.
     */
    public function hasType($alias);
}
