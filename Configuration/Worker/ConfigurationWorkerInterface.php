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
     * @param array $parameters The parameters used to build the value.
     *
     * @return mixed
     */
    public function work(array $parameters = array());
}