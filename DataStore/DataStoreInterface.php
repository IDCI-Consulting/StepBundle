<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DataStore;

interface DataStoreInterface
{
    /**
     * Set a data.
     *
     * @param string $namespace The namespace.
     * @param string $key       The key.
     * @param mixed  $data      The data.
     */
    public function set($namespace, $key, $data);

    /**
     * Get a data.
     *
     * @param string $namespace The namespace.
     * @param string $key       The optional key.
     *
     * @return string|null The serialized value or null if there is no corresponding key or namespace.
     */
    public function get($namespace, $key = null);

    /**
     * Clear data of a namespace.
     *
     * @param string $namespace The namespace.
     */
    public function clear($namespace);
}
