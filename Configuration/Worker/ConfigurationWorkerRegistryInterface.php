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
     *
     * @param string                       $alias  the alias identifier of the worker
     * @param ConfigurationWorkerInterface $worker the worker
     */
    public function setWorker($alias, ConfigurationWorkerInterface $worker);

    /**
     * Get a worker.
     *
     * @param string $alias the alias identifier of the worker
     *
     * @return ConfigurationWorkerInterface the worker
     *
     * @throws \InvalidArgumentException if the worker cannot be retrieved
     */
    public function getWorker($alias);
}
