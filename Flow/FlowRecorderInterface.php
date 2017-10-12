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
     *
     * @param MapInterface $map     the map
     * @param Request      $request the HTTP request
     *
     * @return FlowInterface|null
     */
    public function getFlow(MapInterface $map, Request $request);

    /**
     * Set the flow related with a map and a http request.
     *
     * @param MapInterface  $map     the map
     * @param Request       $request the HTTP request
     * @param FlowInterface $flow    the flow
     */
    public function setFlow(MapInterface $map, Request $request, FlowInterface $flow);

    /**
     * Returns whether or not a Flow already exist in relation with a map and a http request.
     *
     * @param MapInterface $map     the map
     * @param Request      $request the HTTP request
     *
     * @return bool
     */
    public function hasFlow(MapInterface $map, Request $request);

    /**
     * Remove a flow in relalation with a map and a http request.
     *
     * @param MapInterface $map     the map
     * @param Request      $request the HTTP request
     */
    public function removeFlow(MapInterface $map, Request $request);

    /**
     * Clear all flows.
     *
     * @param Request $request the HTTP request
     */
    public function clear(Request $request);

    /**
     * Serialize the given flow.
     *
     * @param FlowInterface $flow the flow
     */
    public function serialize(FlowInterface $flow);

    /**
     * Unserialize a serialized flow.
     *
     * @param string $serializedFlow the serialized flow
     *
     * @return FlowInterface
     */
    public function unserialize($serializedFlow);

    /**
     * Transform flow data if a step data type mapping is defined.
     *
     * @param MapInterface  $map  the map
     * @param FlowInterface $flow the flow
     */
    public function reconstructFlowData(MapInterface $map, FlowInterface $flow);
}
