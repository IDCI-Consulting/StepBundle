<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Map\MapInterface;
use Symfony\Component\HttpFoundation\Request;

interface FlowRecorderInterface
{
    /**
     * Returns the flow related with a map and a http request.
     * If the flow doesn't exist, create it.
     */
    public function getFlow(MapInterface $map, Request $request): ?FlowInterface;

    /**
     * Set the flow related with a map and a http request.
     */
    public function setFlow(MapInterface $map, Request $request, FlowInterface $flow);

    /**
     * Returns whether or not a Flow already exist in relation with a map and a http request.
     */
    public function hasFlow(MapInterface $map, Request $request): bool;

    /**
     * Remove a flow in relalation with a map and a http request.
     */
    public function removeFlow(MapInterface $map, Request $request);

    /**
     * Clear all flows.
     */
    public function clear(Request $request);

    /**
     * Serialize the given flow.
     */
    public function serialize(FlowInterface $flow): string;

    /**
     * Unserialize a serialized flow.
     */
    public function unserialize(string $serializedFlow): FlowInterface;

    /**
     * Transform flow data if a step data type mapping is defined.
     */
    public function reconstructFlowData(MapInterface $map, FlowInterface $flow);
}
