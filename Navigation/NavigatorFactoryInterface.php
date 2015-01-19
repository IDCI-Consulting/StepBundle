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
     * @param Request                                              $request        The HTTP request.
     * @param IDCI\Bundle\StepBundle\Map\MapInterface|array|string $map            The map to navigate.
     * @param array                                                $fetcherOptions The options for the fetcher.
     *
     * @return NavigatorInterface
     */
    public function createNavigator(Request $request, $map, array $options = array());
}