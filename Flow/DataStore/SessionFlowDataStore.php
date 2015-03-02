<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow\DataStore;

use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Flow\Flow;
use IDCI\Bundle\StepBundle\Flow\FlowHistory;
use IDCI\Bundle\StepBundle\Flow\FlowData;

class SessionFlowDataStore implements FlowDataStoreInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
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

        //var_dump($flow->getData()); die;
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
