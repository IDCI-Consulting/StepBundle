<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Registry;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;
use IDCI\Bundle\StepBundle\Path\Type\PathTypeInterface;

class PathRegistry implements PathRegistryInterface
{
    /**
     * @var PathTypeInterface[]
     */
    private $types = array();

    /**
     * {@inheritdoc}
     */
    public function setType($name, PathTypeInterface $path)
    {
        $this->types[$name] = $path;

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
