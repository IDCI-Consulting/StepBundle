<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Step;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;
use IDCI\Bundle\StepBundle\Step\Type\StepTypeInterface;

class StepTypeRegistry implements StepTypeRegistryInterface
{
    /**
     * @var StepTypeInterface[]
     */
    private $types = array();

    /**
     * {@inheritdoc}
     */
    public function setType($alias, StepTypeInterface $step)
    {
        $this->types[$alias] = $step;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType($alias)
    {
        if (!is_string($alias)) {
            throw new UnexpectedTypeException($alias, 'string');
        }

        if (!isset($this->types[$alias])) {
            throw new \InvalidArgumentException(sprintf(
                'Could not load type "%s". Available types are %s',
                $alias,
                implode(', ', array_keys($this->types))
            ));
        }

        return $this->types[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function hasType($alias)
    {
        if (!isset($this->types[$alias])) {
            return false;
        }

        return true;
    }
}
