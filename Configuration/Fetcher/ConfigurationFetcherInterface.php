<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Fetcher;

interface ConfigurationFetcherInterface
{
    /**
     * Fetch a configuration.
     *
     * @param array $parameters the parameters used to fetch the configuration
     *
     * @return array the configuration
     */
    public function fetch(array $parameters = array());
}
