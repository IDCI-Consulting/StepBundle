<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Flow\DataStore\FlowDataStoreInterface;

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
    public function navigate($destination, array $data = null)
    {
        $this->flowProvider->initialize();

        // TODO

        $this->flowProvider->persist();
    }
}
