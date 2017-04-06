<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;
use IDCI\Bundle\StepBundle\Step\Event\Action\StepEventActionInterface;

interface StepEventActionRegistryInterface
{
    /**
     * Sets a step event action identify by alias.
     *
     * @param string                   $alias  The action alias.
     * @param StepEventActionInterface $action The action.
     *
     * @return StepEventActionRegistryInterface
     */
    public function setAction($alias, StepEventActionInterface $action);

    /**
     * Returns a step event action by alias.
     *
     * @param string $alias The alias of the action.
     *
     * @return StepEventActionInterface The action
     *
     * @throws UnexpectedTypeException if the passed alias is not a string.
     * @throws \InvalidArgumentException if the action can not be retrieved.
     */
    public function getAction($alias);

    /**
     * Returns whether the given step event action is supported.
     *
     * @param string $alias The alias of the action.
     *
     * @return bool Whether the action is supported.
     */
    public function hasAction($alias);
}
