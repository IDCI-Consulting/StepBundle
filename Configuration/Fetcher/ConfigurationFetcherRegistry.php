<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Fetcher;

class ConfigurationFetcherRegistry implements ConfigurationFetcherRegistryInterface
{
    protected $fetchers = array();

    /**
     * {@inheritdoc}
     */
    public function setFetcher($alias, ConfigurationFetcherInterface $fetcher)
    {
        $this->fetchers[$alias] = $fetcher;
    }

    /**
     * {@inheritdoc}
     */
    public function getFetcher($alias)
    {
        if (!isset($this->fetchers[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load fetcher "%s"', $alias));
        }

        return $this->fetchers[$alias];
    }
}
