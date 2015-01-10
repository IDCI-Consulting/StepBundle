<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Flow\DataStore;

interface FlowDataStoreRegistryInterface
{
    /**
     * Sets a flow datastore identify by a alias.
     *
     * @param string                 $alias  The store alias.
     * @param FlowDataStoreInterface $store  The store.
     *
     * @return FlowDataStoreRegistryInterface
     */
    public function setStore($alias, FlowDataStoreInterface $store);

    /**
     * Returns a flow datastore by alias.
     *
     * @param string $alias The alias of the flow datastore.
     *
     * @return FlowDataStoreInterface The store
     *
     * @throws Exception\UnexpectedTypeException  if the passed alias is not a string.
     * @throws Exception\InvalidArgumentException if the store can not be retrieved.
     */
    public function getStore($alias);

    /**
     * Returns whether the given flow datastore is supported.
     *
     * @param string $alias The alias of the flow datastore.
     *
     * @return bool Whether the flow datastore is supported.
     */
    public function hasStore($alias);
}
