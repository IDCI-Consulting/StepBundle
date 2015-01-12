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
use IDCI\Bundle\StepBundle\Flow\FlowHistory;
use IDCI\Bundle\StepBundle\Flow\FlowData;

class SessionFlowDataStore implements FlowDataStoreInterface
{
    /**
     * {@inheritdoc}
     */
    public function set(MapInterface $map, Request $request, FlowInterface $flow)
    {
        $currentStep = $flow->getCurrentStep();

        $request->getSession()->set(
            self::generateDataIdentifier($map),
            json_encode(array(
                'current_step' => $currentStep ? $currentStep->getName() : '',
                'data'         => $flow->getData()->getAll(),
                'history'      => $flow->getHistory()->getAll()
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

        if (!empty($flowRow['current_step'])) {
            $flow->setCurrentStep(
                $map->getStep($flowRow['current_step'])
            );
        }

        return $flow
            ->setHistory(new FlowHistory(
                $flowRow['history']['takenPaths'],
                $flowRow['history']['fullTakenPaths']
            ))
            ->setData(new FlowData(
                $flowRow['data']['data'],
                $flowRow['data']['remindedData']
            ))
        ;
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
