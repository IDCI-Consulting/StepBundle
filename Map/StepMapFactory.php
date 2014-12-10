<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Factory;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;
use IDCI\Bundle\StepBundle\Registry\StepRegistryInterface;
use IDCI\Bundle\StepBundle\Type\StepTypeInterface;

class StepFactory implements StepFactoryInterface
{
    /**
     * @var StepRegistryInterface
     */
    private $registry;

    public function __construct(StepRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function create($type = 'flow', $data = null, array $options = array())
    {
        return $this->createBuilder($type, $data, $options)->getStep();
    }

    /**
     * {@inheritdoc}
     */
    public function createNamed($name, $type = 'flow', $data = null, array $options = array())
    {
        return $this->createNamedBuilder($name, $type, $data, $options)->getStep();
    }

    /**
     * {@inheritdoc}
     */
    public function createBuilder($type = 'flow', $data = null, array $options = array())
    {
        $name = $type instanceof StepTypeInterface ? $type->getName() : $type;

        return $this->createNamedBuilder($name, $type, $data, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function createNamedBuilder($name, $type = 'flow', $data = null, array $options = array())
    {
        if (null !== $data && !array_key_exists('data', $options)) {
            $options['data'] = $data;
        }

        if (is_string($type)) {
            $type = $this->registry->getType($type);
        }

        if (!$type instanceof StepTypeInterface) {
            throw new UnexpectedTypeException($type, 'string or IDCI\Bundle\StepBundle\Type\StepTypeInterface');
        }

        return $type->createBuilder($this, $name, $options);
    }
}