<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DataStore;

interface DataStoreRegistryInterface
{
    /**
     * Set a data store.
     *
     * @param string             $alias The alias.
     * @param DataStoreInterface $store The data store.
     *
     * @return DataStoreRegistryInterface This.
     */
    public function set($alias, DataStoreInterface $store);

    /**
     * Get a data store.
     *
     * @param string $alias The alias.
     *
     * @return DataStoreInterface The data store.
     *
     * @throws \InvalidArgumentException If the data store is not registered.
     */
    public function get($alias);

    /**
     * Whether or not a data store is registered.
     *
     * @param string $alias The alias.
     */
    public function has($alias);
}
