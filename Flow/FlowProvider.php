<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Flow\FlowDataStoreInterface;

class FlowProvider implements FlowProviderInterface
{
    /**
     * The data store.
     *
     * @var FlowDataStoreInterface
     */
    protected $dataStore;

    /**
     * Constructor.
     *
     * @param FlowDataStoreInterface $dataStore The data store.
     */
    public function __construct(FlowDataStoreInterface $dataStore)
    {
        $this->dataStore = $dataStore;
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
}
