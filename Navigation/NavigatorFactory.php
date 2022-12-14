<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use IDCI\Bundle\StepBundle\Configuration\Builder\MapConfigurationBuilderInterface;
use IDCI\Bundle\StepBundle\Configuration\Fetcher\ConfigurationFetcherInterface;
use IDCI\Bundle\StepBundle\Configuration\Fetcher\ConfigurationFetcherRegistryInterface;
use IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * Constructor.
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        FlowRecorderInterface $flowRecorder,
        MapConfigurationBuilderInterface $mapConfigurationBuilder,
        ConfigurationFetcherRegistryInterface $configurationFetcherRegistry,
        NavigationLoggerInterface $logger
    ) {
        $this->formFactory = $formFactory;
        $this->flowRecorder = $flowRecorder;
        $this->mapConfigurationBuilder = $mapConfigurationBuilder;
        $this->configurationFetcherRegistry = $configurationFetcherRegistry;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function createNavigator(
        Request $request,
        $configuration,
        array $fetcherParameters = [],
        array $data = [],
        bool $navigate = true
    ): NavigatorInterface {
        if (is_string($configuration)) {
            $configuration = $this->configurationFetcherRegistry->getFetcher($configuration);
        }

        if ($configuration instanceof ConfigurationFetcherInterface) {
            $configuration = $configuration->fetch($fetcherParameters);
        }

        if (is_array($configuration)) {
            $configuration = $this->mapConfigurationBuilder->build($request, $configuration);
        }

        if (!$configuration instanceof MapInterface) {
            throw new \InvalidArgumentException('The map must be an "array", a "reference to a fetcher", '.'an instance of "IDCI\Bundle\StepBundle\Map\MapInterface" '.'or an instance of "IDCI\Bundle\StepBundle\Configuration\Fetcher\ConfigurationFetcherInterface"');
        }

        $navigator = new Navigator(
            $this->formFactory,
            $this->flowRecorder,
            $configuration,
            $request,
            $this->logger,
            $data
        );

        if ($navigate) {
            $navigator->navigate();
        }

        return $navigator;
    }
}
