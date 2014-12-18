<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Flow\DataStore\FlowDataStoreInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;

class FlowNavigator implements FlowNavigatorInterface
{
    /**
     * The flow provider.
     *
     * @var FlowDataStoreInterface
     */
    protected $flowProvider;

    /**
     * Constructor.
     *
     * @param FlowProviderInterface $flowProvider The flow provider.
     */
    public function __construct(
        FlowProviderInterface $flowProvider
    )
    {
        $this->flowProvider = $flowProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataStore(FlowDataStoreInterface $dataStore)
    {
        $this->flowProvider->setDataStore($dataStore);
    }

    /**
     * {@inheritdoc}
     */
    public function navigate(MapInterface $map, $destination, array $data = null)
    {
        $mapName = $map->getName();
        $dataFlow = $this->flowProvider->retrieveDataFlow($mapName);
        $flowDescriptor = $this->flowProvider->retrieveFlowDescriptor($mapName);

        // TODO

        $this->flowProvider->persistDataFlow($mapName, $dataFlow);
        $this->flowProvider->retrieveFlowDescriptor($mapName, $flowDescriptor);
    }
}
