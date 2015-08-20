<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow\DataStore;

use Symfony\Component\HttpFoundation\Request;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;

interface FlowDataStoreInterface
{
    /**
     * Set a flow.
     *
     * @param MapInterface  $map     The map.
     * @param Request       $request The HTTP request.
     * @param string        $data    The serialized navigation flow.
     */
    public function set(MapInterface $map, Request $request, $data);

    /**
     * Get a flow.
     *
     * @param MapInterface  $map     The map.
     * @param Request       $request The HTTP request.
     *
     * @return string|null  The serialized navigation flow or null if not found.
     */
    public function get(MapInterface $map, Request $request);

    /**
     * Clear flow.
     *
     * @param MapInterface  $map     The map.
     * @param Request       $request The HTTP request.
     */
    public function clear(MapInterface $map, Request $request);
}
