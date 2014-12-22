<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use IDCI\Bundle\StepBundle\Flow\DataStore\FlowDataStoreInterface;

interface MapNavigatorInterface
{
    /**
     * Set the data store.
     *
     * @param FlowDataStoreInterface $dataStore The data store.
     */
    public function setDataStore(FlowDataStoreInterface $dataStore);

    /**
     * Navigate to a step.
     *
     * @param MapInterface $map      The map.
     * @param string       $stepName The destination step name.
     */
    public function navigate(MapInterface $map, $stepName);
}