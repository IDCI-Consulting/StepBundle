<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event;

use IDCI\Bundle\StepBundle\Path\Event\Action\PathEventActionInterface;

interface PathEventRegistryInterface
{
    /**
     * Sets a path event action identify by alias.
     *
     * @param string                   $alias  The action alias.
     * @param PathEventActionInterface $action The action.
     *
     * @return PathEventActionRegistryInterface
     */
    public function setAction($alias, PathEventActionInterface $action);

    /**
     * Returns a path event action by alias.
     *
     * @param string $alias The alias of the action.
     *
     * @return PathEventActionInterface The action
     *
     * @throws Exception\UnexpectedTypeException  if the passed alias is not a string.
     * @throws Exception\InvalidArgumentException if the action can not be retrieved.
     */
    public function getAction($alias);

    /**
     * Returns whether the given path event action is supported.
     *
     * @param string $alias The alias of the action.
     *
     * @return bool Whether the action is supported.
     */
    public function hasAction($alias);
}
