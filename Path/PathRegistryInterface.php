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
     * Sets a path type identify by a name
     *
     * @param string            $name   The path name
     * @param PathTypeInterface $path   The path
     *
     * @return PathRegistryInterface
     */
    public function setType($name, PathTypeInterface $path);

    /**
     * Returns a path type by name.
     *
     * @param string $name The name of the type
     *
     * @return PathTypeInterface The type
     *
     * @throws Exception\UnexpectedTypeException  if the passed name is not a string
     * @throws Exception\InvalidArgumentException if the type can not be retrieved
     */
    public function getType($name);

    /**
     * Returns whether the given path type is supported.
     *
     * @param string $name The name of the type
     *
     * @return bool Whether the type is supported
     */
    public function hasType($name);
}
