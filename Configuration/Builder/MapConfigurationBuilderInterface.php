<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Builder;

interface MapConfigurationBuilderInterface
{
    /**
     * Build the configuration.
     *
     * @param array $parameters The parameters used to build the configuration.
     *
     * @return array
     */
    public function build(array $parameters = array());
}