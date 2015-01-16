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
     * @param array $optionsContainer The array containing the options in a field "options".
     *
     * @return array The formatted options.
     */
    protected function formatOptions(array $optionsContainer)
    {
        if (!isset($optionsContainer['options'])) {
            return array();
        }

        return $this->formatOptionField($optionsContainer['options']);
    }

    /**
     * Format an option field.
     *
     * @param array $optionField The option field.
     *
     * @return array The formatted field.
     */
    protected function formatOptionField(array $optionField)
    {
        foreach ($optionField as $key => $value) {
            // Case of a worker.
            if ('@' === substr($key, 0, 1)) {
                $worker = $this->workerRegistry->getWorker($value['worker']);

                unset($optionField[$key]);
                $optionField[substr($key, 1)] = $worker->work($value['parameters']);
            // Case of an embedded array.
            } else if (is_array($value)) {
                $optionField[$key] = $this->formatOptionField($value);
            }
        }

        return $optionField;
    }
}