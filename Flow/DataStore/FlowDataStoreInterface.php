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
     * @param string $mapName  The identifier name of the map.
     * @param string $flowName The identifier name of the flow.
     * @param string $step     The serialized value of the flow.
     */
    public function save($mapName, $flowName, $step);

    /**
     * Retrieve.
     *
     * @param string $mapName  The identifier name of the map.
     * @param string $flowName The identifier name of the flow.
     *
     * @return string|null The serialized value of the flow or null if there is no corresponding saved flow.
     */
    public function retrieve($mapName, $flowName);
}
