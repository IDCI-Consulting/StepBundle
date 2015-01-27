<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Builder;

use IDCI\Bundle\StepBundle\Map\MapBuilderFactoryInterface;
use IDCI\Bundle\StepBundle\Configuration\Worker\ConfigurationWorkerRegistryInterface;

class MapConfigurationBuilder implements MapConfigurationBuilderInterface
{
    /**
     * The map builder factory.
     *
     * @var MapBuilderFactoryInterface
     */
    protected $mapBuilderFactory;


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
     * @param ConfigurationWorkerRegistryInterface $workerRegistry    The configuration worker registry.
     */
    public function __construct(
        MapBuilderFactoryInterface $mapBuilderFactory,
        ConfigurationWorkerRegistryInterface $workerRegistry
    )
    {
        $this->mapBuilderFactory = $mapBuilderFactory;
        $this->workerRegistry = $workerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function build(array $parameters = array())
    {
        // Build the map.
        $mapOptions = $this->formatOptions($parameters);

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
                    $this->formatOptions($step)
                );
            }
        }

        if (isset($parameters['paths'])) {
            foreach ($parameters['paths'] as $name => $path) {
                $builder->addPath(
                    $path['type'],
                    $this->formatOptions($path)
                );
            }
        }

        return $builder->getMap();
    }

    /**
     * Format options.
     *
     * @param array   $options       The options.
     * @param boolean $isOptionnable True if the given options must contain an 'option' sub field.
     *
     * @return array The formatted options.
     */
    protected function formatOptions(array $options, $isOptionnable = true)
    {
        if ($isOptionnable) {
            $options = isset($options['options']) ? $options['options'] : array();
        }

        foreach ($options as $key => $option) {
            // Case of a worker.
            if ('@' === substr($key, 0, 1)) {
                $worker = $this->workerRegistry->getWorker($option['worker']);

                unset($options[$key]);
                $options[substr($key, 1)] = $worker->work($option['parameters']);
            // Case of an embedded array.
            } else if (is_array($option)) {
                $options[$key] = $this->formatOptions($option, false);
            }
        }

        return $options;
    }
}
