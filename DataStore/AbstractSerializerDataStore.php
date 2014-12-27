<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DataStore;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

abstract class AbstractSerializerDataStore implements DataStoreInterface
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
                json_encode($decodedData['_data']),
                $decodedData['_metadata']['class'],
                'json'
            );
        }

        return $decodedData;
    }
}
