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
use IDCI\Bundle\StepBundle\Configuration\Builder\MapConfigurationBuilderInterface;
use IDCI\Bundle\StepBundle\Configuration\Fetcher\ConfigurationFetcherRegistryInterface;

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
     * @var MapConfigurationBuilderInterface
     */
    private $mapConfigurationBuilder;

    /**
     * @var ConfigurationFetcherRegistryInterface
     */
    private $configurationFetcherRegistry;

    /**
     * @var NavigationLoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param FormFactoryInterface                  $formFactory                  The form factory.
     * @param FlowDataStoreRegistryInterface        $flowDataStoreRegistry        The flow data store registry.
     * @param MapConfigurationBuilderInterface      $mapConfigurationBuilder      The map configuration builder.
     * @param ConfigurationFetcherRegistryInterface $configurationFetcherRegistry The configuration fetcher registry.
     * @param NavigationLoggerInterface             $logger                       The logger.
     */
    public function __construct(
        FormFactoryInterface                  $formFactory,
        FlowDataStoreRegistryInterface        $flowDataStoreRegistry,
        MapConfigurationBuilderInterface      $mapConfigurationBuilder,
        ConfigurationFetcherRegistryInterface $configurationFetcherRegistry,
        NavigationLoggerInterface             $logger
    )
    {
        $this->formFactory                  = $formFactory;
        $this->flowDataStoreRegistry        = $flowDataStoreRegistry;
        $this->mapConfigurationBuilder       = $mapConfigurationBuilder;
        $this->configurationFetcherRegistry = $configurationFetcherRegistry;
        $this->logger                       = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function createNavigator(Request $request, $map, array $options = array())
    {
        if (is_string($map)) {
            $map = $this->configurationFetcherRegistry->getFetcher($map)->fetch($options);
        } elseif (!empty($options)) {
            throw new \InvalidArgumentException('You can specify options only when you use a reference to a fetcher');
        }

        if (is_array($map)) {
            $map = $this->mapConfigurationBuilder->build($map);
        }

        if (!$map instanceof MapInterface) {
            throw new \InvalidArgumentException(
                'The map must be an "array", a "reference to a fetcher" or an instance of "IDCI\Bundle\StepBundle\Map\MapInterface"'
            );
        }

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