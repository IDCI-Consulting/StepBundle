<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow\DataStore;

interface FlowDataStoreInterface
{
    /**
     * Save.
     *
     * @param string $name The identifier name of the flow.
     * @param string $step The serialized value of the flow.
     */
    public function save($name, $step);

    /**
     * Retrieve.
     *
     * @param string $name The identifier name of the flow.
     *
     * @return string|null The serialized value of the flow or null if there is no corresponding saved flow.
     */
    public function retrieve($name);
}
