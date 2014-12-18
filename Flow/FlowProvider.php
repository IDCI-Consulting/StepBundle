<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use JMS\Serializer\SerializerInterface;
use IDCI\Bundle\StepBundle\Flow\DataFlowInterface;
use IDCI\Bundle\StepBundle\Flow\FlowDescriptorInterface;
use IDCI\Bundle\StepBundle\Flow\DataStore\FlowDataStoreInterface;

class FlowProvider implements FlowProviderInterface
{
    /**
     * The data store.
     *
     * @var FlowDataStoreInterface
     */
    protected $dataStore;

    /**
     * The serializer.
     *
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * The class for the data flow.
     *
     * @var string
     */
    protected $dataFlowClass;

    /**
     * The class for the flow descriptor.
     *
     * @var FlowDataStoreInterface
     */
    protected $flowDescriptorClass;

    /**
     * Constructor.
     *
     * @param FlowDataStoreInterface $dataStore           The data store.
     * @param SerializerInterface    $serializer          The serializer.
     * @param string                 $dataFlowClass       The class for the data flow.
     * @param string                 $flowDescriptorClass The class for the flow descriptor.
     */
    public function __construct(
        FlowDataStoreInterface $dataStore,
        SerializerInterface $serializer,
        $dataFlowClass,
        $flowDescriptorClass
    )
    {
        $this->dataStore = $dataStore;
        $this->serializer = $serializer;
        $this->dataFlowClass = $dataFlowClass;
        $this->flowDescriptorClass = $flowDescriptorClass;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataStore(FlowDataStoreInterface $dataStore)
    {
        $this->dataStore = $dataStore;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveDataFlow($mapName)
    {
        $data = $this->dataStore->retrieve($mapName, 'data');

        if ($data) {
            $dataFlow = $this->serializer->deserialize(
                $data,
                $dataFlowClass,
                'json'
            );
        } else {
            $dataFlow = new $dataFlowClass();
        }

        if (!($dataFlow instanceof DataFlowInterface)) {
            throw new \InvalidArgumentException(
                'The class for the flow descriptor must be an instance of "IDCI\Bundle\StepBundle\Flow\DataFlowInterface".'
            );
        }

        return $dataFlow;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveFlowDescriptor($mapName)
    {
        $data = $this->dataStore->retrieve($mapName, 'descriptor');

        if ($data) {
            $flowDescriptor = $this->serializer->deserialize(
                $data,
                $flowDescriptorClass,
                'json'
            );
        } else {
            $flowDescriptor = new $flowDescriptorClass();
        }

        if (!($flowDescriptor instanceof FlowDescriptorInterface)) {
            throw new \InvalidArgumentException(
                'The class for the flow descriptor must be an instance of "IDCI\Bundle\StepBundle\Flow\FlowDescriptorInterface".'
            );
        }

        return $flowDescriptor;
    }

    /**
     * {@inheritdoc}
     */
    public function persistDataFlow($mapName, DataFlowInterface $dataFlow)
    {
        $data = $this->serializer->serialize($dataFlow, 'json');
        $this->dataStore->save($mapName, 'data', $data);
    }

    /**
     * {@inheritdoc}
     */
    public function persistFlowDescriptor($mapName, FlowDescriptorInterface $flowDescriptor)
    {
        $descriptor = $this->serializer->serialize($flowDescriptor, 'json');
        $this->dataStore->save($mapName, 'descriptor', $descriptor);
    }
}
