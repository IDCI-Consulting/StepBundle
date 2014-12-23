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
        $mapConfig = $map->getConfiguration();

        return new FormNavigator(
            $this->formFactory,
            $this->registry->get($mapConfig['options']['data_store']),
            $map,
            $request
        );
    }
}