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
     * @param MapInterface $map     The map.
     * @param Request      $request The HTTP request.
     *
     * @return FlowInterface
     */
    public function getFlow(MapInterface $map, Request $request);

    /**
     * Set the flow related with a map and a http request.
     *
     * @param MapInterface  $map     The map.
     * @param Request       $request The HTTP request.
     * @param FlowInterface $flow    The flow.
     */
    public function setFlow(MapInterface $map, Request $request, FlowInterface $flow);

    /**
     * Returns whether or not a Flow already exist in relation with a map and a http request.
     *
     * @param MapInterface $map     The map.
     * @param Request      $request The HTTP request.
     *
     * @return boolean
     */
    public function hasFlow(MapInterface $map, Request $request);

    /**
     * Clear a flow in relalation with a map and a http request.
     *
     * @param MapInterface $map     The map.
     * @param Request      $request The HTTP request.
     */
    public function clearFlow(MapInterface $map, Request $request);

    /**
     * Serialize the given flow.
     *
     * @param FlowInterface $flow The flow.
     */
    public function serialize(FlowInterface $flow);

    /**
     * Unserialize a serialized flow.
     *
     * @param string $serializedFlow The serialized flow.
     *
     * @return FlowInterface
     */
    public function unserialize($serializedFlow);
}