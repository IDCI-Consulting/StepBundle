<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Serialization;

use JMS\Serializer\Serializer;
use JMS\Serializer\TypeParser;
use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use Metadata\MetadataFactoryInterface;
use PhpCollection\MapInterface;
use PhpCollection\Map;

class SerializerProvider implements SerializerProviderInterface
{
    private $factory;
    private $handlerRegistry;
    private $objectConstructor;
    private $dispatcher;
    private $typeParser;

    /** @var \PhpCollection\MapInterface */
    private $serializationVisitors;

    /** @var \PhpCollection\MapInterface */
    private $deserializationVisitors;

    /**
     * Constructor.
     *
     * @param \Metadata\MetadataFactoryInterface $factory
     * @param Handler\HandlerRegistryInterface $handlerRegistry
     * @param Construction\ObjectConstructorInterface $objectConstructor
     * @param \PhpCollection\MapInterface<VisitorInterface> $serializationVisitors
     * @param \PhpCollection\MapInterface<VisitorInterface> $deserializationVisitors
     * @param EventDispatcher\EventDispatcherInterface $dispatcher
     * @param TypeParser $typeParser
     */
    public function __construct(MetadataFactoryInterface $factory, HandlerRegistryInterface $handlerRegistry, ObjectConstructorInterface $objectConstructor, MapInterface $serializationVisitors, MapInterface $deserializationVisitors, EventDispatcherInterface $dispatcher = null, TypeParser $typeParser = null)
    {
        $this->factory = $factory;
        $this->handlerRegistry = $handlerRegistry;
        $this->objectConstructor = $objectConstructor;
        $this->dispatcher = $dispatcher;
        $this->typeParser = $typeParser ?: new TypeParser();
        $this->serializationVisitors = $serializationVisitors;
        $this->deserializationVisitors = $deserializationVisitors;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $serializationVisitors = new Map();
        $deserializationVisitors = new Map();

        foreach (iterator_to_array($this->serializationVisitors) as $key => $visitor) {
            $serializationVisitors->set($key, clone $visitor);
        }

        foreach (iterator_to_array($this->deserializationVisitors) as $key => $visitor) {
            $deserializationVisitors->set($key, clone $visitor);
        }

        return new Serializer(
            $this->factory,
            $this->handlerRegistry,
            $this->objectConstructor,
            $serializationVisitors,
            $deserializationVisitors,
            $this->dispatcher,
            $this->typeParser
        );
    }
}