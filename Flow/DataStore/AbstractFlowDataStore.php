<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow\DataStore;

use Symfony\Component\HttpFoundation\Request;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Flow\FlowData;

abstract class AbstractFlowDataStore implements FlowDataStoreInterface
{
    /**
     * Generate the data identifier used to store and retrieve the data flow.
     *
     * @param MapInterface $map The map.
     *
     * @return string The flow data identifier used in this store.
     */
    public static function generateDataIdentifier(MapInterface $map)
    {
        return sprintf('idci_step.flow_data.%s', $map->getFingerPrint());
    }

    /**
     * {@inheritdoc}
     */
    public function set(MapInterface $map, Request $request, $data)
    {
        $this->store($request, self::generateDataIdentifier($map), $data);
    }

    /**
     * {@inheritdoc}
     */
    public function get(MapInterface $map, Request $request)
    {
        return $this->retrieve($request, self::generateDataIdentifier($map));
    }

    /**
     * {@inheritdoc}
     */
    public function clear(MapInterface $map, Request $request)
    {
        $this->doClear($request, self::generateDataIdentifier($map));
    }

    /**
     * Store data.
     *
     * @param Request $request The request.
     * @param string  $id      The data identifier.
     * @param mixed   $data    The data.
     */
    abstract public function store(Request $request, $id, $data);

    /**
     * Retrieve data.
     *
     * @param Request $request The request.
     * @param string  $id      The data identifier.
     */
    abstract public function retrieve(Request $request, $id);

    /**
     * Clear the data.
     *
     * @param Request $request The request.
     * @param string  $id      The data identifier.
     */
    abstract public function doClear(Request $request, $id);
}
