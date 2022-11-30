<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Worker;

class ConfigurationWorkerRegistry implements ConfigurationWorkerRegistryInterface
{
    /**
     * @var ConfigurationWorkerInterface[]
     */
    protected $workers = [];

    /**
     * {@inheritdoc}
     */
    public function setWorker(string $alias, ConfigurationWorkerInterface $worker)
    {
        $this->workers[$alias] = $worker;
    }

    /**
     * {@inheritdoc}
     */
    public function getWorker(string $alias): ConfigurationWorkerInterface
    {
        if (!isset($this->workers[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load worker "%s"', $alias));
        }

        return $this->workers[$alias];
    }
}
