<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 *
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Fetcher;

class ConfigurationFetcherRegistry implements ConfigurationFetcherRegistryInterface
{
    protected $fetchers = [];

    public function setFetcher(string $alias, ConfigurationFetcherInterface $fetcher)
    {
        $this->fetchers[$alias] = $fetcher;
    }

    public function getFetcher(string $alias): ConfigurationFetcherInterface
    {
        if (!isset($this->fetchers[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load fetcher "%s"', $alias));
        }

        return $this->fetchers[$alias];
    }
}
