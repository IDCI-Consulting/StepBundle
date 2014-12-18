<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Flow\DataStore\FlowDataStoreInterface;

interface FlowProviderInterface
{
    /**
     * Set the data store.
     *
     * @param FlowDataStoreInterface $dataStore The data store.
     */
    public function setDataStore(FlowDataStoreInterface $dataStore);

    /**
     * Retrieve the data flow.
     *
     * @param string $mapName The identifier name of the map.
     *
     * @return DataFlowInterface The data flow object.
     */
    public function retrieveDataFlow($mapName);

    /**
     * Persist the data flow.
     *
     * @param string            $mapName  The identifier name of the map.
     * @param DataFlowInterface $dataFlow The data flow object.
     */
    public function persistDataFlow($mapName, DataFlowInterface $dataFlow);

    /**
     * Retrieve the flow descriptor.
     *
     * @param string $mapName The identifier name of the map.
     *
     * @return FlowDescriptorInterface The flow descriptor object.
     */
    public function retrieveFlowDescriptor($mapName);

    /**
     * Persist the flow descriptor.
     *
     * @param string                  $mapName        The identifier name of the map.
     * @param FlowDescriptorInterface $flowDescriptor The flow descriptor object.
     */
    public function persistFlowDescriptor($mapName, FlowDescriptorInterface $flowDescriptor);
}
