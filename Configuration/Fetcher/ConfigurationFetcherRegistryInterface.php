<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Fetcher;

interface ConfigurationFetcherRegistryInterface
{
    /**
     * Set a fetcher.
     */
    public function setFetcher(string $alias, ConfigurationFetcherInterface $fetcher);

    /**
     * Get a fetcher.
     */
    public function getFetcher(string $alias): ConfigurationFetcherInterface;
}
