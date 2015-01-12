<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;
use IDCI\Bundle\StepBundle\Flow\DataStore\FlowDataStoreInterface;

class FlowDataStoreRegistry implements FlowDataStoreRegistryInterface
{
    /**
     * @var FlowDataStoreInterface[]
     */
    private $stores = array();

    /**
     * {@inheritdoc}
     */
    public function setStore($alias, FlowDataStoreInterface $store)
    {
        $this->stores[$alias] = $store;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStore($alias)
    {
        if (!is_string($alias)) {
            throw new UnexpectedTypeException($alias, 'string');
        }

        if (!isset($this->stores[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load flow datastore "%s"', $alias));
        }

        return $this->stores[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function hasStore($alias)
    {
        return isset($this->stores[$alias]);
    }
}
