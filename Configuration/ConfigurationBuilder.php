<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration;

use IDCI\Bundle\StepBundle\Map\MapBuilderFactoryInterface;
use IDCI\Bundle\StepBundle\Configuration\Worker\ConfigurationWorkerRegistryInterface;

class ConfigurationBuilder implements ConfigurationBuilderInterface
{
    /**
     * The map builder factory.
     *
     * @var MapBuilderFactoryInterface
     */
    protected $mapBuilderFactory;

    /**
     * The configuration processor.
     *
     * @var ConfigurationProcessorInterface
     */
    protected $processor;

    /**
     * The configuration worker registry.
     *
     * @var ConfigurationWorkerRegistryInterface
     */
    protected $workerRegistry;

    /**
     * Constructor.
     *
     * @param MapBuilderFactoryInterface           $mapBuilderFactory The map builder factory.
     * @param ConfigurationProcessorInterface      $processor         The configuration processor.
     * @param ConfigurationWorkerRegistryInterface $workerRegistry    The configuration worker registry.
     */
    public function __construct(
        MapBuilderFactoryInterface $mapBuilderFactory,
        ConfigurationProcessorInterface $processor,
        ConfigurationWorkerRegistryInterface $workerRegistry
    )
    {
        $this->mapBuilderFactory = $mapBuilderFactory;
        $this->processor = $processor;
        $this->workerRegistry = $workerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function build(array $parameters = array())
    {
        // Check and process the structure of the parameters.
        $parameters = $this->processor->process(array('dumb' => $parameters));

        // Build the map.
        $mapOptions = $this->formatOptions($parameters['options']);

        $mapData = isset($parameters['data'])
            ? $parameters['data']
            : array()
        ;

        $builder = $this->mapBuilderFactory
            ->createNamedBuilder($parameters['name'], $mapData, $mapOptions)
        ;

        if (isset($parameters['steps'])) {
            foreach ($parameters['steps'] as $name => $step) {
                $builder->addStep(
                    $name,
                    $step['type'],
                    $this->formatOptions($step['options'])
                );
            }
        }

        if (isset($parameters['paths'])) {
            foreach ($parameters['paths'] as $name => $path) {
                $builder->addPath(
                    $path['type'],
                    $this->formatOptions($path['options'])
                );
            }
        }

        return $builder->getMap();
    }

    /**
     * Format options.
     *
     * @param array $options The options.
     *
     * @return array The formatted options.
     */
    protected function formatOptions(array $options)
    {
        foreach ($options as $key => $option) {
            // Case of a worker.
            if ('@' === substr($key, 0, 1)) {
                $worker = $this->workerRegistry->getWorker($option['worker']);

                unset($options[$key]);
                $options[substr($key, 1)] = $worker->work($option['parameters']);
            // Case of an embedded array.
            } else if (is_array($option)) {
                $options[$key] = $this->formatOptions($option);
            }
        }

        return $options;
    }
}