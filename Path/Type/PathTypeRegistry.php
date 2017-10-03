<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

class PathTypeRegistry implements PathTypeRegistryInterface
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
            throw new \InvalidArgumentException(sprintf(
                'Could not load path type "%s". Available types are %s',
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
