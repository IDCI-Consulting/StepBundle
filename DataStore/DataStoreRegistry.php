<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DataStore;

class DataStoreRegistry implements DataStoreRegistryInterface
{
    /**
     * The registered data stores.
     *
     * @var array
     */
    private $stores = array();

    /**
     * {@inheritdoc}
     */
    public function set($alias, DataStoreInterface $store)
    {
        $this->stores[$alias] = $store;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($alias)
    {
        if (!isset($this->stores[$alias])) {
            throw new \InvalidArgumentException(sprintf('No data store "%s" found.', $alias));
        }

        return $this->stores[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function has($alias)
    {
        return isset($this->stores[$alias]);
    }
}
