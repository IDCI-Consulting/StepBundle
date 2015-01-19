<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration;

use Symfony\Component\Config\Definition\Processor;

class ConfigurationProcessor implements ConfigurationProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(array $config)
    {
        $processor = new Processor();
        $configuration = new MapConfiguration();

        return $processor->processConfiguration($configuration, $config);
    }
}