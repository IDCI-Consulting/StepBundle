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
use IDCI\Bundle\StepBundle\DataStore\DataStoreRegistryInterface;
use IDCI\Bundle\StepBundle\Flow\FlowEventNotifierInterface;

class NavigatorFactory implements NavigatorFactoryInterface
{
    /**
     * @var DataStoreRegistryInterface
     */
    private $registry;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FlowEventNotifierInterface
     */
    protected $flowEventNotifier;

    /**
     * @var NavigationLoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param DataStoreRegistryInterface $registry          The data store registry.
     * @param FormFactoryInterface       $formFactory       The form factory.
     * @param FlowEventNotifierInterface $flowEventNotifier The flow event notifier.
     * @param NavigationLoggerInterface  $logger            The data store registry.
     */
    public function __construct(
        DataStoreRegistryInterface $registry,
        FormFactoryInterface       $formFactory,
        FlowEventNotifierInterface $flowEventNotifier,
        NavigationLoggerInterface  $logger
    )
    {
        $this->registry          = $registry;
        $this->formFactory       = $formFactory;
        $this->flowEventNotifier = $flowEventNotifier;
        $this->logger            = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function createNavigator(MapInterface $map, Request $request)
    {
        return new Navigator(
            $this->formFactory,
            $this->guessDataStore($map),
            $map,
            $request,
            $this->flowEventNotifier,
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
    private function guessDataStore(MapInterface $map)
    {
        $mapConfig = $map->getConfiguration();

        return $this->registry->get($mapConfig['options']['data_store']);
    }
}