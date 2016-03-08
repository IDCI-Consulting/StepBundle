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
     * @param Request                                                   $request        The HTTP request.
     * @param MapInterface|ConfigurationFetcherInterface|array|string   $configuration  The map configuration.
     * @param array                                                     $parameters     The fetcher parameters.
     * @param array                                                     $flowData       The navigation flow data.
     * @param boolean                                                   $navigate       Whether or not to do the navigation during creation.
     *
     * @return NavigatorInterface
     */
    public function createNavigator(Request $request, $configuration, array $parameters = array(), array $flowData = array(), $navigate = true);
}