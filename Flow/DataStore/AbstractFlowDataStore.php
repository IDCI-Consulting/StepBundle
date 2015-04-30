<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow\DataStore;

use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\DeserializationContext;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Flow\FlowData;

abstract class AbstractFlowDataStore implements FlowDataStoreInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Constructor
     *
     * @param SerializerInterface $serializer The serializer.
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

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
     * Transform data
     *
     * @param mixed $data    The data to transform.
     * @param array $mapping The mapping, containing the expected data type and optionnaly the serialization groups.
     *
     * @return The transformed data.
     */
    protected function transformData($data, array $mapping)
    {
        if (gettype($data) === $mapping['type'] || $data instanceof $mapping['type']) {
            return $data;
        }

        $context = new DeserializationContext();
        if (!empty($mapping['groups'])) {
            $context->setGroups($mapping['groups']);
        }

        return $this->serializer->deserialize(
            json_encode($data),
            $mapping['type'],
            'json',
            $context
        );
    }

    /**
     * Transform flow data if a step data type mapping is defined
     *
     * @param FlowData     $flowData The flow data.
     * @param MapInterface $map      The map.
     */
    public function reconstructFlowData(FlowData $flowData, MapInterface $map)
    {
        foreach ($map->getSteps() as $stepName => $step) {
            foreach (array(null, FlowData::TYPE_REMINDED) as $dataType) {
                if ($flowData->hasStepData($stepName, $dataType)) {
                    $mapping = $step->getDataTypeMapping();
                    $data = $flowData->getStepData($stepName, $dataType);
                    $transformed = array();
                    foreach ($data as $field => $value) {
                        if (null === $value || (is_array($value) && empty($value))) {
                            continue;
                        }

                        if (isset($mapping[$field])) {
                            $transformed[$field] = $this->transformData($value, $mapping[$field]);
                        }
                    }
                    if (!empty($transformed)) {
                        $flowData->setStepData(
                            $stepName,
                            array_replace_recursive($data, $transformed),
                            $dataType
                        );
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set(MapInterface $map, Request $request, FlowInterface $flow)
    {
        $this->store(
            $request,
            self::generateDataIdentifier($map),
            $this->serializer->serialize($flow, 'json')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get(MapInterface $map, Request $request)
    {
        $data = $this->retrieve(
            $request,
            self::generateDataIdentifier($map)
        );

        if (null === $data) {
            return null;
        }

        $flow = $this->serializer->deserialize(
            $data,
            'IDCI\Bundle\StepBundle\Flow\Flow',
            'json'
        );

        $this->reconstructFlowData($flow->getData(), $map);

        return $flow;
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
