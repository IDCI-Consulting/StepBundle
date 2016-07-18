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
     * @param string                       $alias  The alias identifier of the worker.
     * @param ConfigurationWorkerInterface $worker The worker.
     */
    public function setWorker($alias, ConfigurationWorkerInterface $worker);

    /**
     * Get a worker.
     *
     * @param string $alias The alias identifier of the worker.
     *
     * @return ConfigurationWorkerInterface The worker.
     *
     * @throws \InvalidArgumentException if the worker cannot be retrieved.
     */
    public function getWorker($alias);
}
