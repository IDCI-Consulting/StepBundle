<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Builder;

use IDCI\Bundle\StepBundle\Map\MapInterface;
use Symfony\Component\HttpFoundation\Request;

interface MapConfigurationBuilderInterface
{
    /**
     * Build the configuration.
     */
    public function build(Request $request, array $parameters = []): MapInterface;
}
