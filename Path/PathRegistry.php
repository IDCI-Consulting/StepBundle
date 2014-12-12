<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Path;

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
    public function setType($alias, PathTypeInterface $path)
    {
        $this->types[$alias] = $path;

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
            throw new \InvalidArgumentException(sprintf('Could not load type "%s"', $alias));
        }

        return $this->types[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function hasType($alias)
    {
        if (!isset($this->types[$alias])) {
            return true;
        }

        return false;
    }
}
