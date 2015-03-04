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
use IDCI\Bundle\StepBundle\Flow\Flow;
use IDCI\Bundle\StepBundle\Flow\FlowHistory;
use IDCI\Bundle\StepBundle\Flow\FlowData;
use IDCI\Bundle\StepBundle\Flow\DataStore\Serialization\SerializationMapper;

class SessionFlowDataStore implements FlowDataStoreInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var SerializationMapper
     */
    protected $mapper;

    /**
     * Constructor
     *
     * @param SerializerInterface $serializer The serializer.
     * @param SerializationMapper $mapper     The serialization mapper.
     */
    public function __construct(SerializerInterface $serializer, SerializationMapper $mapper)
    {
        $this->serializer = $serializer;
        $this->mapper     = $mapper;
    }

    /**
     * {@inheritdoc}
     */
    public function set(MapInterface $map, Request $request, FlowInterface $flow)
    {
        $request->getSession()->set(
            self::generateDataIdentifier($map),
            $this->serializer->serialize($flow, 'json')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get(MapInterface $map, Request $request)
    {
        if (!$request->getSession()->has(self::generateDataIdentifier($map))) {
            return null;
        }

        $flow = $this->serializer->deserialize(
            $request
                ->getSession()
                ->get(self::generateDataIdentifier($map))
            ,
            'IDCI\Bundle\StepBundle\Flow\Flow',
            'json'
        );

        $this->reconstructFlowData($flow->getData());

        return $flow;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(MapInterface $map, Request $request)
    {
        $request
            ->getSession()
            ->remove(self::generateDataIdentifier($map))
        ;
    }

    /**
     * Reconstruct the flow data
     *
     * @param FlowData $flowData the deserialized flowData.
     */
    protected function reconstructFlowData(FlowData $flowData)
    {
        foreach ($flowData->getFormTypeMapping() as $step => $fields) {
            foreach ($fields as $field => $formType) {
                $mapping = $this->mapper->map('form_types', $formType);
                if (null !== $mapping) {
                    $this->reconstructData($flowData, $step, $field, $mapping);
                    $this->reconstructData($flowData, $step, $field, $mapping, FlowData::TYPE_REMINDED);
                }
            }
        }
    }

    /**
     * Transform data
     *
     * @param FlowData $flowData The flow data to transform.
     * @param string   $step     The step name.
     * @param string   $field    The field step name.
     * @param array    $mapping  The mapping transformation (The type and the context).
     * @param string   $dataType The flow data type.
     */
    protected function reconstructData(FlowData & $flowData, $step, $field, array $mapping, $dataType = null)
    {
        if ($flowData->hasStepData($step, $dataType)) {
            $data = $flowData->getStepData($step, $dataType);
            if (isset($data[$field])) {
                $context = new DeserializationContext();
                if (!empty($mapping['groups'])) {
                    $context->setGroups($mapping['groups']);
                }
                $data[$field] = $this->serializer->deserialize(
                    json_encode($data[$field]),
                    $mapping['type'],
                    'json',
                    $context
                );
                $flowData->setStepData($step, $data, array(), $dataType);
            }
        }
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
}
