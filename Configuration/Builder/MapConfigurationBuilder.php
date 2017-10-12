<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Builder;

use Symfony\Component\HttpFoundation\Request;
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
     * @param MapBuilderFactoryInterface           $mapBuilderFactory the map builder factory
     * @param ConfigurationWorkerRegistryInterface $workerRegistry    the configuration worker registry
     */
    public function __construct(
        MapBuilderFactoryInterface $mapBuilderFactory,
        ConfigurationWorkerRegistryInterface $workerRegistry
    ) {
        $this->mapBuilderFactory = $mapBuilderFactory;
        $this->workerRegistry = $workerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function build(Request $request, array $parameters = array())
    {
        $builder = $this
            ->mapBuilderFactory
            ->createNamedBuilder(
                $parameters['name'],
                isset($parameters['data']) ? $parameters['data'] : array(),
                $this->buildOptions($parameters)
            )
        ;

        if (isset($parameters['steps'])) {
            foreach ($parameters['steps'] as $name => $step) {
                $builder->addStep(
                    $name,
                    $step['type'],
                    $this->buildOptions($step)
                );
            }
        }

        if (isset($parameters['paths'])) {
            foreach ($parameters['paths'] as $name => $path) {
                $builder->addPath(
                    $path['type'],
                    $this->buildOptions($path)
                );
            }
        }

        return $builder->getMap($request);
    }

    /**
     * Build options.
     *
     * @param array       $options     the options
     * @param string|null $optionField the option sub field to build if given
     *
     * @return array the built options
     */
    protected function buildOptions(array $options, $optionField = 'options')
    {
        if (null !== $optionField) {
            $options = isset($options[$optionField]) ? $options[$optionField] : array();
        }

        foreach ($options as $key => $option) {
            // Case of a worker.
            if ('@' === substr($key, 0, 1)) {
                $worker = $this->workerRegistry->getWorker($option['worker']);

                unset($options[$key]);
                $options[substr($key, 1)] = $worker->work($option['parameters']);
                // Case of an embedded array.
            } elseif (is_array($option)) {
                $options[$key] = $this->buildOptions($option, null);
            }
        }

        return $options;
    }
}
