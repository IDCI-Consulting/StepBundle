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
     *
     * @param string                        $alias   The alias identifier of the fetcher.
     * @param ConfigurationFetcherInterface $fetcher The fetcher.
     */
    public function setFetcher($alias, ConfigurationFetcherInterface $fetcher);

    /**
     * Get a fetcher.
     *
     * @param string $alias The alias identifier of the fetcher.
     *
     * @return ConfigurationFetcherInterface The fetcher.
     *
     * @throws \InvalidArgumentException if the fetcher cannot be retrieved.
     */
    public function getFetcher($alias);
}