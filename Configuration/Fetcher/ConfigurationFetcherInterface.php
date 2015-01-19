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
     * @param array $parameters The parameters used to fetch the configuration.
     *
     * @return array The configuration.
     */
    public function fetch(array $parameters = array());
}