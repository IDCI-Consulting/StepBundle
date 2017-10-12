<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Builder;

use Symfony\Component\HttpFoundation\Request;

interface MapConfigurationBuilderInterface
{
    /**
     * Build the configuration.
     *
     * @param Request $request    the HTTP request
     * @param array   $parameters the parameters used to build the configuration
     *
     * @return array
     */
    public function build(Request $request, array $parameters = array());
}
