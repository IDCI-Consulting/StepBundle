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
     * @param MapInterface $map         The map.
     * @param string       $destination The identifier name of the destination step.
     * @param array        $data        The data for the previous step.
     */
    public function navigate(MapInterface $map, $destination, array $data = null);
}
