<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration;

interface ConfigurationProcessorInterface
{
    /**
     * Process a config.
     *
     * @param array $config The config to process.
     *
     * @return array The processed config.
     */
    public function process(array $config);
}