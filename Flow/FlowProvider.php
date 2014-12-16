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
     * The data flow.
     *
     * @var FlowDataStoreInterface
     */
    protected $dataFlow;

    /**
     * The flow descriptor.
     *
     * @var FlowDataStoreInterface
     */
    protected $flowDescriptor;

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
        $this->setDataFlow(new $dataFlowClass());
        $this->setFlowDescriptor(new $flowDescriptorClass());
    }

    /**
     * Set the data store.
     *
     * @param FlowDataStoreInterface $dataStore The data store.
     */
    public function setDataStore(FlowDataStoreInterface $dataStore)
    {
        $this->dataStore = $dataStore;
    }

    /**
     * Set the data flow.
     *
     * @param DataFlowInterface $dataFlow The data flow.
     */
    public function setDataFlow(DataFlowInterface $dataFlow)
    {
        $this->dataFlow = $dataFlow;
    }

    /**
     * Set the flow descriptor.
     *
     * @param FlowDescriptorInterface $flowDescriptor The flow descriptor.
     */
    public function setFlowDescriptor(FlowDescriptorInterface $flowDescriptor)
    {
        $this->flowDescriptor = $flowDescriptor;
    }

    /**
     * Initialize the flows.
     */
    public function initialize()
    {
        $data = $this->dataStore->retrieve('data');

        if ($data) {
            $this->dataFlow = $this->serializer->deserialize(
                $data,
                get_class($this->dataFlow),
                'json'
            );
        }

        $descriptor = $this->dataStore->retrieve('descriptor');

        if ($descriptor) {
            $this->flowDescriptor = $this->serializer->deserialize(
                $descriptor,
                get_class($this->flowDescriptor),
                'json'
            );
        }
    }

    /**
     * Persist the flows.
     */
    public function persist()
    {
        $data = $this->serializer->serialize($this->dataFlow, 'json');
        $this->dataStore->save('data', $data);

        $descriptor = $this->serializer->serialize($this->flowDescriptor, 'json');
        $this->dataStore->save('descriptor', $descriptor);
    }
}
