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
     * Constructor
     *
     * @param DataStoreRegistryInterface $registry The data store registry.
     */
    public function __construct(
        DataStoreRegistryInterface $registry,
        FormFactoryInterface $formFactory
    )
    {
        $this->registry    = $registry;
        $this->formFactory = $formFactory;
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
            $request
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