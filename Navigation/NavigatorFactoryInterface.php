<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use IDCI\Bundle\ExtraFormBundle\Configuration\Fetcher\ConfigurationFetcherInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use Symfony\Component\HttpFoundation\Request;

interface NavigatorFactoryInterface
{
    /**
     * Create a Navigator.
     *
     * @param Request the HTTP request
     * @param MapInterface|ConfigurationFetcherInterface|array|string the map configuration
     * @param array the fetcher parameters
     * @param array the navigation data
     * @param bool whether or not to do the navigation during creation
     */
    public function createNavigator(
        Request $request,
        $configuration,
        array $fetcherParameters = [],
        array $data = [],
        bool $navigate = true
    ): NavigatorInterface;
}
