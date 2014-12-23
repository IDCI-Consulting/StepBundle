<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DataStore;

use JMS\Serializer\SerializerInterface;

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
                $dataClass,
                'json'
            );

            $arrayData = json_decode($serializedData, true);

            return json_encode(array(
                '_data' => $arrayData,
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
            $dataClass = get_class($data);

            return $this->serializer->serialize(
                json_encode($decodedData['_data']),
                $decodedData['_metadata']['class'],
                'json'
            );
        }

        return json_encode($data);
    }
}
