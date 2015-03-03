<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Serializer;

class SerializationMapper
{
    /**
     * @var array
     */
    protected $mapping;

    /**
     * Constructor
     */
    public function __constructor($mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * Returns the mapped value if exists
     *
     * @param $namespace The namespace.
     * @param $key       The key.
     *
     * @return string
     *
     * @throws UnexpectedValueException
     */
    public function map($namespace, $key)
    {
        if (!isset($this->mapping[$namespace])) {
            throw new \UnexpectedValueException(sprintf(
                'The given namespace "%s" doesn\'t exist',
                $namespace
            ));
        }

        if (!isset($this->mapping[$namespace][$key])) {
            throw new \UnexpectedValueException(sprintf(
                'The namespace "%s" doesn\'t contain "%s" key',
                $namespace, $key
            ));
        }

        return $this->mapping[$namespace][$key]
    }
}