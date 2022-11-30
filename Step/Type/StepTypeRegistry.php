<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

class StepTypeRegistry implements StepTypeRegistryInterface
{
    /**
     * @var StepTypeInterface[]
     */
    private $types = [];

    /**
     * {@inheritdoc}
     */
    public function setType(string $alias, StepTypeInterface $step): StepTypeRegistryInterface
    {
        $this->types[$alias] = $step;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(string $alias): StepTypeInterface
    {
        if (!isset($this->types[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load type "%s". Available types are %s', $alias, implode(', ', array_keys($this->types))));
        }

        return $this->types[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function hasType(string $alias): bool
    {
        if (!isset($this->types[$alias])) {
            return false;
        }

        return true;
    }
}
