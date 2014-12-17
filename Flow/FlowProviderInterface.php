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
     * Initialize the flows.
     */
    public function initialize();

    /**
     * Persist the flows.
     */
    public function persist();
}
