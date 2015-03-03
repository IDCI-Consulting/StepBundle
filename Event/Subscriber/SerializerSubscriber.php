<?php

namespace IDCI\Bundle\StepBundle\Event\Subscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\EventDispatcher\Events;
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

        if ($object instanceof \IDCI\Bundle\StepBundle\Flow\FlowData) {
            foreach ($object->getFormTypeMapping() as $step => $fields) {
                foreach ($fields as $field => $formType) {
                    $fieldType = $this->mapper->map('form_types', $formType);
                    if (null !== $fieldType) {
                        if ($object->hasStepData($step, FlowData::TYPE_REMINDED)) {
                            $data = $object->getStepData($step, FlowData::TYPE_REMINDED);
                            if (isset($data[$field])) {
                                $typeParser = new \JMS\Serializer\TypeParser();
                                $data[$field] = $context
                                    ->getNavigator()
                                    ->accept(
                                        $context->getVisitor()->prepare(json_encode($data[$field])),
                                        $typeParser->parse($fieldType),
                                        $context
                                    )
                                ;
                                $object->setStepData($step, $data, array(), FlowData::TYPE_REMINDED);
                            }
                        }
                    }
                }
            }
        }
    }
}
