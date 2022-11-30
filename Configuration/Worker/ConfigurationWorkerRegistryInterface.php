<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Worker;

interface ConfigurationWorkerRegistryInterface
{
    /**
     * Set a worker.
     */
    public function setWorker(string $alias, ConfigurationWorkerInterface $worker);

    /**
     * Get a worker.
     */
    public function getWorker(string $alias): ConfigurationWorkerInterface;
}
