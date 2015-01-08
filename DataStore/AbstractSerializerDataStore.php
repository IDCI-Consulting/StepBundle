<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DataStore;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\EventDispatcher\Events;

abstract class AbstractSerializerDataStore implements DataStoreInterface,  EventSubscriberInterface
{
    /**
     * The serializer.
     *
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Constructor.
     *
     * @param SessionInterface    $session    The session.
     * @param SerializerInterface $serializer The serializer.
     */
    public function __construct(
        SerializerInterface $serializer
    )
    {
        $this->serializer = $serializer;
    }

    /**
     * Serialize a data.
     *
     * @param mixed $data The data.
     *
     * @return string The JSON serialization.
     */
    protected function serialize($data)
    {
        // Handle object case.
        if (is_object($data)) {
            $dataClass = get_class($data);

            $serializedData = $this->serializer->serialize(
                $data,
                'json',
                SerializationContext::create()->setGroups(array('idci_step.navigation'))
            );

            return json_encode(array(
                '_data' => json_decode($serializedData, true),
                '_metadata' => array(
                    'class' => $dataClass
                )
            ));
        }

        return json_encode($data);
    }

    /**
     * Serialize the data in an array.
     *
     * @param array $data The data.
     *
     * @return array The array with serialized data.
     */
    protected function serializeArray(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->serializeArray($value);
            } else if (is_object($value)) {
                $data[$key] = $this->serialize($value);
            }
        }

        return $data;
    }

    /**
     * Deserialize a data.
     *
     * @param string $data The JSON encoded data.
     *
     * @return mixed The deserialized data.
     */
    protected function deserialize($data)
    {
        $decodedData = json_decode($data, true);

        // Handle object case.
        if (is_array($decodedData) && isset($decodedData['_metadata'])) {
            return $this->serializer->deserialize(
                json_encode(
                    $this->deserializeArray($decodedData['_data'])
                ),
                $decodedData['_metadata']['class'],
                'json'
            );
        }

        return $decodedData;
    }

    /**
     * Deserialize the data in an array.
     *
     * @param array $data The data.
     *
     * @return array The array with deserialized data.
     */
    protected function deserializeArray(array $data)
    {
        foreach ($data as $key => $value) {
            // Handle object case.
            if (is_array($value) && isset($value['_metadata'])) {
                $realData = $value['_data'];
                if (is_array($realData)) {
                    $realData = $this->deserializeArray($realData);
                }

                $data[$key] = $this->serializer->deserialize(
                    json_encode($realData),
                    $value['_metadata']['class'],
                    'json'
                );
            }
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            array(
                'event'     => Events::PRE_SERIALIZE,
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'method'    => 'onPreSerialize',
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function onPreSerialize(ObjectEvent $event)
    {
        $object = $event->getObject();

        $class = new \ReflectionClass($object);
        $properties = $class->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($object);

            if (is_array($value)) {
                $property->setValue(
                    $object,
                    $this->serializeArray($value)
                );
            }
        }
    }
}
