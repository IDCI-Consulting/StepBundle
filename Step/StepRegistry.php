<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Step;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;
use IDCI\Bundle\StepBundle\Step\Type\StepTypeInterface;

class StepRegistry implements StepRegistryInterface
{
    /**
     * @var StepTypeInterface[]
     */
    private $types = array();

    /**
     * {@inheritdoc}
     */
    public function setType($name, StepTypeInterface $step)
    {
        $this->types[$name] = $step;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType($name)
    {
        if (!is_string($name)) {
            throw new UnexpectedTypeException($name, 'string');
        }

        if (!isset($this->types[$name])) {
            throw new \InvalidArgumentException(sprintf('Could not load type "%s"', $name));
        }

        return $this->types[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasType($name)
    {
        if (!isset($this->types[$name])) {
            return true;
        }

        return false;
    }
}
