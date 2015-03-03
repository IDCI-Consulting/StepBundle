<?php

namespace IDCI\Bundle\StepBundle\Serializer\Handler;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\Context;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\XmlDeserializationVisitor;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\XmlSerializationVisitor;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use IDCI\Bundle\StepBundle\Flow\FlowData;
use IDCI\Bundle\StepBundle\Serializer\SerializationMapper;

class FlowDataHandler implements SubscribingHandlerInterface
{
    /**
     * @var SerializationMapper
     */
    protected $mapper;

    /**
     * Constructor
     *
     * @param SerializationMapper $mapper     The serialization mapper.
     */
    public function __construct(SerializationMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = array();

        foreach (array('json', 'xml', 'yml') as $format) {
            $methods[] = array(
                'type' => 'IDCI\Bundle\StepBundle\Flow\FlowData',
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => $format,
                'method' => 'deserializeFlowData'
            );
        }

        return $methods;
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeFlowData(VisitorInterface $visitor, $data, array $type, Context $context)
    {
        foreach ($data['formTypeMapping'] as $step => $fields) {
            foreach ($fields as $field => $formType) {
                $fieldType = $this->mapper->map('form_types', $formType);
                if (null !== $fieldType) {
                    if (isset($data['remindedData'][$step][$field])) {
                        $typeParser = new \JMS\Serializer\TypeParser();
                        $data['remindedData'][$step][$field] = $context
                            ->getNavigator()
                            ->accept(
                                $visitor->prepare(json_encode($data['remindedData'][$step][$field])),
                                $typeParser->parse($fieldType),
                                $context
                            )
                        ;
                    }

                    if (isset($data['data'][$step][$field])) {
                        $typeParser = new \JMS\Serializer\TypeParser();
                        $data['data'][$step][$field] = $context
                            ->getNavigator()
                            ->accept(
                                $visitor->prepare(json_encode($data['data'][$step][$field])),
                                $typeParser->parse($fieldType),
                                $context
                            )
                        ;
                    }
                }
            }
        }

        return new FlowData(
            $data['formTypeMapping'],
            $data['data'],
            $data['remindedData'],
            $data['retrievedData']
        );
    }
}
