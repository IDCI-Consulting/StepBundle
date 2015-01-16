<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\HttpFoundation\Request;
use IDCI\Bundle\StepBundle\Map\MapInterface;

interface NavigatorFactoryInterface
{
    /**
     * Create a Navigator
     *
     * @param IDCI\Bundle\StepBundle\Map\MapInterface|array $map     The map to navigate.
     * @param Request                                       $request The HTTP request.
     *
     * @return NavigatorInterface
     */
    public function createNavigator($map, Request $request);
}