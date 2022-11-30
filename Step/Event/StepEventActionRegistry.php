<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event;

use IDCI\Bundle\StepBundle\Step\Event\Action\StepEventActionInterface;

class StepEventActionRegistry implements StepEventActionRegistryInterface
{
    /**
     * @var StepEventActionInterface[]
     */
    private $actions = [];

    /**
     * {@inheritdoc}
     */
    public function setAction(string $alias, StepEventActionInterface $action): StepEventActionRegistryInterface
    {
        $this->actions[$alias] = $action;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAction(string $alias): StepEventActionInterface
    {
        if (!isset($this->actions[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load step event action "%s". Available actions are %s', $alias, implode(', ', array_keys($this->actions))));
        }

        return $this->actions[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function hasAction(string $alias): bool
    {
        if (!isset($this->actions[$alias])) {
            return true;
        }

        return false;
    }
}
