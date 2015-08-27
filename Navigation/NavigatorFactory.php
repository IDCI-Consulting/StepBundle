<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use JMS\Serializer\SerializerInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Flow\FlowDataStoreRegistryInterface;
use IDCI\Bundle\StepBundle\Configuration\Builder\MapConfigurationBuilderInterface;
use IDCI\Bundle\StepBundle\Configuration\Fetcher\ConfigurationFetcherRegistryInterface;
use IDCI\Bundle\StepBundle\Configuration\Fetcher\ConfigurationFetcherInterface;

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
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Constructor
     *
     * @param FormFactoryInterface                  $formFactory                  The form factory.
     * @param FlowDataStoreRegistryInterface        $flowDataStoreRegistry        The flow data store registry.
     * @param MapConfigurationBuilderInterface      $mapConfigurationBuilder      The map configuration builder.
     * @param ConfigurationFetcherRegistryInterface $configurationFetcherRegistry The configuration fetcher registry.
     * @param NavigationLoggerInterface             $logger                       The logger.
     * @param SerializerInterface                   $serializer                   The serializer.
     */
    public function __construct(
        FormFactoryInterface                  $formFactory,
        FlowDataStoreRegistryInterface        $flowDataStoreRegistry,
        MapConfigurationBuilderInterface      $mapConfigurationBuilder,
        ConfigurationFetcherRegistryInterface $configurationFetcherRegistry,
        NavigationLoggerInterface             $logger,
        SerializerInterface                   $serializer
    )
    {
        $this->formFactory                  = $formFactory;
        $this->flowDataStoreRegistry        = $flowDataStoreRegistry;
        $this->mapConfigurationBuilder      = $mapConfigurationBuilder;
        $this->configurationFetcherRegistry = $configurationFetcherRegistry;
        $this->logger                       = $logger;
        $this->serializer                   = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function createNavigator(Request $request, $configuration, array $parameters = array(), array $data = array())
    {
        if (is_string($configuration)) {
            $configuration = $this->configurationFetcherRegistry->getFetcher($configuration);
        }

        if ($configuration instanceof ConfigurationFetcherInterface) {
            $configuration = $configuration->fetch($parameters);
        }

        if (is_array($configuration)) {
            $configuration = $this->mapConfigurationBuilder->build($configuration);
        }

        if (!$configuration instanceof MapInterface) {
            throw new \InvalidArgumentException(
                'The map must be an "array", a "reference to a fetcher", an instance of "IDCI\Bundle\StepBundle\Map\MapInterface" or an instance of "IDCI\Bundle\StepBundle\Configuration\Fetcher\ConfigurationFetcherInterface"'
            );
        }

        return new Navigator(
            $this->formFactory,
            $request,
            $configuration,
            $data,
            $this->guessFlowDataStore($configuration),
            $this->logger,
            $this->serializer
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