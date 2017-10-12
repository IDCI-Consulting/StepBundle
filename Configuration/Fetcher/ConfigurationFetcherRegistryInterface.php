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
     * @param string                        $alias   the alias identifier of the fetcher
     * @param ConfigurationFetcherInterface $fetcher the fetcher
     */
    public function setFetcher($alias, ConfigurationFetcherInterface $fetcher);

    /**
     * Get a fetcher.
     *
     * @param string $alias the alias identifier of the fetcher
     *
     * @return ConfigurationFetcherInterface the fetcher
     *
     * @throws \InvalidArgumentException if the fetcher cannot be retrieved
     */
    public function getFetcher($alias);
}
