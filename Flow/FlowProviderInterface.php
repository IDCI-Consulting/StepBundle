<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

interface FlowProviderInterface
{
    /**
     * Initialize the flows.
     */
    public function initialize();

    /**
     * Persist the flows.
     */
    public function persist();
}
