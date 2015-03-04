<?php

namespace IDCI\Bundle\StepBundle\Event\Subscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\DeserializationContext;
use IDCI\Bundle\StepBundle\Serializer\SerializationMapper;
use IDCI\Bundle\StepBundle\Flow\FlowData;

/**
 * SerializerSubscriber
 *
 * @author Gabriel Bondaz <gabriel.bondaz@idci-consulting.fr>
 */
class SerializerSubscriber implements EventSubscriberInterface
{
    /**
     * @var SerializationMapper
     */
    protected $mapper;

    /**
     * Constructor
     *
     * @param SerializationMapper $mapper The serialization mapper.
     */
    public function __construct(SerializationMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            array(
                'class'     => 'IDCI\Bundle\StepBundle\Flow\FlowData',
                'event'     => Events::POST_DESERIALIZE,
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'method'    => 'onPostDeserialize',
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function onPostDeserialize(ObjectEvent $event)
    {
        $object  = $event->getObject();
        $context = $event->getContext();

        foreach ($object->getFormTypeMapping() as $step => $fields) {
            foreach ($fields as $field => $formType) {
                $fieldType = $this->mapper->map('form_types', $formType);
                if (null !== $fieldType) {
                    $this->transformData($object, $step, $field, $fieldType, $context);
                    $this->transformData($object, $step, $field, $fieldType, $context, FlowData::TYPE_REMINDED);
                }
            }
        }
    }

    /**
     * Transform data
     *
     * @param FlowData               $object   The flow data to transform.
     * @param string                 $step     The step name.
     * @param string                 $field    The field step name.
     * @param string                 $type     To transform into this type.
     * @param DeserializationContext $context  The deserialization context.
     * @param string                 $dataType 
     */
    protected function transformData(
        FlowData & $object,
        $step,
        $field,
        $type,
        DeserializationContext $context,
        $dataType = null
    )
    {
        if ($object->hasStepData($step, $dataType)) {
            $data = $object->getStepData($step, $dataType);
            if (isset($data[$field])) {
                $typeParser = new \JMS\Serializer\TypeParser();
                $data[$field] = $context->getNavigator()->accept(
                    $data[$field],
                    $typeParser->parse($type),
                    $context
                );
                $object->setStepData($step, $data, array(), $dataType);
            }
        }
    }
}
