<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Worker;

interface ConfigurationWorkerInterface
{
    /**
     * Build and return a value or object from the configuration.
     *
     * @return mixed
     */
    public function work(array $parameters = []);
}
