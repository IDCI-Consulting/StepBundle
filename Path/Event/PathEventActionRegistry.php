<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 *
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event;

use IDCI\Bundle\StepBundle\Path\Event\Action\PathEventActionInterface;

class PathEventActionRegistry implements PathEventActionRegistryInterface
{
    /**
     * @var PathEventActionInterface[]
     */
    private $actions = [];

    public function setAction(string $alias, PathEventActionInterface $action): PathEventActionRegistryInterface
    {
        $this->actions[$alias] = $action;

        return $this;
    }

    public function getAction(string $alias): PathEventActionInterface
    {
        if (!isset($this->actions[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load path event action "%s". Available actions are %s', $alias, implode(', ', array_keys($this->actions))));
        }

        return $this->actions[$alias];
    }

    public function hasAction(string $alias): bool
    {
        if (!isset($this->actions[$alias])) {
            return true;
        }

        return false;
    }
}
