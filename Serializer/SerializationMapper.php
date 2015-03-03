<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Serializer;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

class SerializationMapper
{
    /**
     * @var array
     */
    protected $mapping;

    /**
     * Constructor
     */
    public function __construct($mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * Returns the mapped value if exists
     *
     * @param $namespace The namespace.
     * @param $key       The key.
     *
     * @return string|null
     *
     * @throws UnexpectedTypeException
     * @throws InvalidArgumentException
     */
    public function map($namespace, $key)
    {
        if (!is_string($namespace)) {
            throw new UnexpectedTypeException($namespace, 'string');
        }

        if (!is_string($key)) {
            throw new UnexpectedTypeException($key, 'string');
        }

        if (!isset($this->mapping[$namespace])) {
            throw new \InvalidArgumentException(sprintf(
                'The given namespace "%s" doesn\'t exist',
                $namespace
            ));
        }

        if (!isset($this->mapping[$namespace][$key])) {
            return null;
        }

        return $this->mapping[$namespace][$key];
    }
}