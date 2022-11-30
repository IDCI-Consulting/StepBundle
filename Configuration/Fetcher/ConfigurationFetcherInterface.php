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
     */
    public function fetch(array $parameters = []): array;
}
