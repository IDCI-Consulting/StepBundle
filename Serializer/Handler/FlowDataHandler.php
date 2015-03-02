<?php

namespace IDCI\Bundle\StepBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\XmlDeserializationVisitor;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\XmlSerializationVisitor;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use IDCI\Bundle\StepBundle\Flow\FlowData;

class FlowDataHandler implements SubscribingHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = array();
        $types = array('DateTime', 'DateInterval');

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
        var_dump($data);
        if ($context->attributes->containsKey('navigator')) {
            $navigator = $context->attributes->get('navigator')->get();
        }

        return new FlowData(
            $data['data'],
            $data['remindedData'],
            $data['retrievedData']
        );
    }
}
