<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DataStore;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\EventDispatcher\Events;
use IDCI\Bundle\StepBundle\Serialization\SerializerProviderInterface;

abstract class AbstractSerializerDataStore implements DataStoreInterface,  EventSubscriberInterface
{
    /**
     * The serializer provider.
     *
     * @var SerializerProviderInterface
     */
    protected $serializerProvider;

    /**
     * Constructor.
     *
     * @param SerializerProviderInterface $serializerProvider The serializer provider.
     */
    public function __construct(
        SerializerProviderInterface $serializerProvider
    )
    {
        $this->serializerProvider = $serializerProvider;
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

            $serializedData = $this->serializerProvider->get()->serialize(
                clone $data,
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
        if (is_array($decodedData)) {
            if (isset($decodedData['_metadata'])) {
                $deserializedData = $this->serializerProvider->get()->deserialize(
                    json_encode(
                        $decodedData['_data']
                    ),
                    $decodedData['_metadata']['class'],
                    'json'
                );

                return $this->deserializeObject(clone $deserializedData);
            } else {
                return $this->deserializeArray($decodedData);
            }
        }

        return $decodedData;
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
                $data[$key] = json_decode($this->serialize($value), true);
            }
        }

        return $data;
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
        if (isset($value['_metadata'])) {
            $realData = $value['_data'];
            if (is_array($realData)) {
                $realData = $this->deserializeArray($realData);
            }

            $data = $this->serializerProvider->get()->deserialize(
                json_encode($realData),
                $value['_metadata']['class'],
                'json'
            );
        } else {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    if (isset($value['_metadata'])) {
                        $realData = $value['_data'];
                        if (is_array($realData)) {
                            $realData = $this->deserializeArray($realData);
                        }

                        $value = $this->serializerProvider->get()->deserialize(
                            json_encode($realData),
                            $value['_metadata']['class'],
                            'json'
                        );

                        $data[$key] = $this->deserializeObject($value);
                    } else {
                        $data[$key] = $this->deserializeArray($value);
                    }
                } elseif (is_object($value)) {
                    $data[$key] = $this->deserializeObject(clone $value);
                }
            }
        }

        return $data;
    }

    /**
     * Serialize the data in an object.
     *
     * @param array $data The data.
     *
     * @return array The object with serialized data.
     */
    protected function serializeObject($object)
    {
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
            } elseif (is_object($value)) {
                $property->setValue(
                    $object,
                    $this->serializeObject(clone $value)
                );
            }
        }

        return $object;
    }

    /**
     * Deserialize the data in an object.
     *
     * @param array $data The data.
     *
     * @return array The object with deserialized data.
     */
    protected function deserializeObject($object)
    {
        $class = new \ReflectionClass($object);
        $properties = $class->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($object);

            if (is_array($value)) {
                $property->setValue(
                    $object,
                    $this->deserializeArray($value)
                );

            } elseif (is_object($value)) {
                $property->setValue(
                    $object,
                    $this->deserializeObject(clone $value)
                );
            }
        }

        if ($object instanceof \DateTime) {
            // Set the current timezone.
            $now = new \DateTime();
            $object->setTimezone($now->getTimezone());
        }

        return $object;
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
        $this->serializeObject($event->getObject());
    }
}
