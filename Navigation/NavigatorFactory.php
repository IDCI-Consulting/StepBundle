<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface;
use IDCI\Bundle\StepBundle\Configuration\Builder\MapConfigurationBuilderInterface;
use IDCI\Bundle\StepBundle\Configuration\Fetcher\ConfigurationFetcherRegistryInterface;
use IDCI\Bundle\StepBundle\Configuration\Fetcher\ConfigurationFetcherInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;

class NavigatorFactory implements NavigatorFactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FlowRecorderInterface
     */
    private $flowRecorder;

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
     * @param FlowRecorderInterface                 $flowRecorder                 The flow recorder.
     * @param MapConfigurationBuilderInterface      $mapConfigurationBuilder      The map configuration builder.
     * @param ConfigurationFetcherRegistryInterface $configurationFetcherRegistry The configuration fetcher registry.
     * @param NavigationLoggerInterface             $logger                       The logger.
     */
    public function __construct(
        FormFactoryInterface                  $formFactory,
        FlowRecorderInterface                 $flowRecorder,
        MapConfigurationBuilderInterface      $mapConfigurationBuilder,
        ConfigurationFetcherRegistryInterface $configurationFetcherRegistry,
        NavigationLoggerInterface             $logger
    )
    {
        $this->formFactory                  = $formFactory;
        $this->flowRecorder                 = $flowRecorder;
        $this->mapConfigurationBuilder      = $mapConfigurationBuilder;
        $this->configurationFetcherRegistry = $configurationFetcherRegistry;
        $this->logger                       = $logger;
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
            $this->flowRecorder,
            $configuration,
            $request,
            $this->logger,
            $data
        );
    }
}