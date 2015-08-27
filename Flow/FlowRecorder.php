<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use Symfony\Component\HttpFoundation\Request;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\DeserializationContext;

class FlowRecorder implements FlowRecorderInterface
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
     * {@inheritdoc}
     */
    public function getFlow(MapInterface $map, Request $request)
    {
        if (!$this->hasFlow($map, $request)) {
            return null;
        }

        $flow = $this->unserialize($request
            ->getSession()
            ->get($map->getFootprint()
        ));
        $this->reconstructFlowData($map, $flow);

        return $flow;
    }

    /**
     * {@inheritdoc}
     */
    public function setFlow(MapInterface $map, Request $request, FlowInterface $flow)
    {
        $request->getSession()->set(
            $map->getFootprint(),
            $this->serialize($flow)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function hasFlow(MapInterface $map, Request $request)
    {
        return $request->getSession()->has($map->getFootprint());
    }

    /**
     * {@inheritdoc}
     */
    public function clearFlow(MapInterface $map, Request $request)
    {
        $request->getSession()->remove($map->getFootprint());
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(FlowInterface $flow)
    {
        return $this->serializer->serialize($flow, 'json');
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serializedFlow)
    {
        return $this->serializer->deserialize(
            $serializedFlow,
            'IDCI\Bundle\StepBundle\Flow\Flow',
            'json'
        );
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
     */
    protected function reconstructFlowData(MapInterface $map, FlowInterface & $flow)
    {
        foreach ($map->getSteps() as $stepName => $step) {
            if ($flow->hasStepData($step)) {
                $mapping = $step->getDataTypeMapping();
                $data = $flow->getStepData($step);
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
                    $flow->setStepData(
                        $step,
                        array_replace_recursive($data, $transformed)
                    );
                }
            }
        }
    }
}