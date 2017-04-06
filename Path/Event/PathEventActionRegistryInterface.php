<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event;

use IDCI\Bundle\ExtraStepBundle\Exception\UnexpectedTypeException;
use IDCI\Bundle\StepBundle\Path\Event\Action\PathEventActionInterface;

interface PathEventActionRegistryInterface
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
     * @throws UnexpectedTypeException  if the passed alias is not a string.
     * @throws \InvalidArgumentException if the action can not be retrieved.
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
