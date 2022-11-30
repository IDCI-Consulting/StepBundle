<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Serialization;

class SerializationMapper
{
    /**
     * @var array
     */
    protected $mapping;

    /**
     * Constructor.
     */
    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * Returns the mapped value if exists.
     *
     * @throws InvalidArgumentException
     */
    public function map(string $namespace, string $key): ?array
    {
        if (!isset($this->mapping[$namespace])) {
            throw new \InvalidArgumentException(sprintf('The given namespace "%s" doesn\'t exist', $namespace));
        }

        if (!isset($this->mapping[$namespace][$key])) {
            return null;
        }

        return $this->mapping[$namespace][$key];
    }
}
