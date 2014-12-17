<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Flow\DataStore\FlowDataStoreInterface;

interface FlowNavigatorInterface
{
    /**
     * Set the data store.
     *
     * @param FlowDataStoreInterface $dataStore The data store.
     */
    public function setDataStore(FlowDataStoreInterface $dataStore);

    /**
     * Navigate in the steps.
     *
     * @param string $destination The identifier name of the destination step.
     * @param array  $data        The data for the previous step.
     */
    public function navigate($destination, array $data = null);
}
