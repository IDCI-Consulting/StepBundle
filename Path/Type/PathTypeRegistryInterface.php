<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

interface PathTypeRegistryInterface
{
    /**
     * Sets a path type identify by a alias.
     *
     * @param string            $alias the type alias
     * @param PathTypeInterface $path  the type
     *
     * @return PathTypeRegistryInterface
     */
    public function setType($alias, PathTypeInterface $path);

    /**
     * Returns a path type by alias.
     *
     * @param string $alias the alias of the type
     *
     * @return PathTypeInterface The type
     *
     * @throws UnexpectedTypeException   if the passed alias is not a string
     * @throws \InvalidArgumentException if the type can not be retrieved
     */
    public function getType($alias);

    /**
     * Returns whether the given path type is supported.
     *
     * @param string $alias the alias of the type
     *
     * @return bool whether the type is supported
     */
    public function hasType($alias);
}
