<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Flow\FlowDataStoreRegistryInterface;

class NavigatorFactory implements NavigatorFactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FlowDataStoreRegistryInterface
     */
    private $flowDataStoreRegistry;

    /**
     * @var NavigationLoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param FormFactoryInterface           $formFactory           The form factory.
     * @param FlowDataStoreRegistryInterface $flowDataStoreRegistry The flow data store registry.
     * @param NavigationLoggerInterface      $logger                The logger.
     */
    public function __construct(
        FormFactoryInterface            $formFactory,
        FlowDataStoreRegistryInterface  $flowDataStoreRegistry,
        NavigationLoggerInterface       $logger
    )
    {
        $this->formFactory              = $formFactory;
        $this->flowDataStoreRegistry    = $flowDataStoreRegistry;
        $this->logger                   = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function createNavigator(MapInterface $map, Request $request)
    {
        return new Navigator(
            $this->formFactory,
            $map,
            $request,
            $this->guessFlowDataStore($map),
            $this->logger
        );
    }

    /**
     * Guess a data store based on a map configuration
     *
     * @param MapInterface $map The map.
     *
     * @return DataStoreInterface
     */
    private function guessFlowDataStore(MapInterface $map)
    {
        $mapConfig = $map->getConfiguration();

        return $this
            ->flowDataStoreRegistry
            ->getStore($mapConfig['options']['data_store'])
        ;
    }
}