<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow\DataStore;

use Symfony\Component\HttpFoundation\Request;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Flow\Flow;

class SessionFlowDataStore implements FlowDataStoreInterface
{
    /**
     * {@inheritdoc}
     */
    public function set(MapInterface $map, Request $request, FlowInterface $flow)
    {
        $request->getSession()->set(
            self::generateDataIdentifier($map),
            json_encode(array(
                'current_step' => $flow->getCurrentStep(),
                'data'         => $flow->getData()->getAll(),
                'history'      => array(
                    'takenPaths'     => $flow->getHistory()->getTakenPaths(),
                    'fullTakenPaths' => $flow->getHistory()->getFullTakenPaths(),
                ),
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function get(MapInterface $map, Request $request)
    {
        if (!$request->getSession()->has(self::generateDataIdentifier($map))) {
            return null;
        }

        $flowRow = json_decode(
            $request
                ->getSession()
                ->get(self::generateDataIdentifier($map))
            ,
            true
        );

        $flow = new Flow();

        return $flow
            ->setCurrentStep($flowRow['current_step'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(MapInterface $map, Request $request)
    {
    
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
