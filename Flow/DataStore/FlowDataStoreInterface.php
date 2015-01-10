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
     * @param FlowInterface $flow    The flow.
     */
    public function set(MapInterface $map, Request $request, FlowInterface $flow);

    /**
     * Get a flow.
     *
     * @param MapInterface  $map     The map.
     * @param Request       $request The HTTP request.
     *
     * @return FlowInterface|null The flow or null if there is no corresponding flow.
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
