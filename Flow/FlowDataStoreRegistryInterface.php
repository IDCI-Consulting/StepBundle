<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Flow\DataStore\FlowDataStoreInterface;

interface FlowDataStoreRegistryInterface
{
    /**
     * Sets a flow data store identify by a alias.
     *
     * @param string                 $alias  The store alias.
     * @param FlowDataStoreInterface $store  The store.
     *
     * @return FlowDataStoreRegistryInterface
     */
    public function setStore($alias, FlowDataStoreInterface $store);

    /**
     * Returns a flow data store by alias.
     *
     * @param string $alias The alias of the flow data store.
     *
     * @return FlowDataStoreInterface The store
     *
     * @throws Exception\UnexpectedTypeException  if the passed alias is not a string.
     * @throws Exception\InvalidArgumentException if the store can not be retrieved.
     */
    public function getStore($alias);

    /**
     * Returns whether the given flow data store is supported.
     *
     * @param string $alias The alias of the flow data store.
     *
     * @return bool Whether the flow data store is supported.
     */
    public function hasStore($alias);
}
