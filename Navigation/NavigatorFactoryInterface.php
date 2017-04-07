<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\HttpFoundation\Request;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\ExtraFormBundle\Configuration\Fetcher\ConfigurationFetcherInterface;

interface NavigatorFactoryInterface
{
    /**
     * Create a Navigator
     *
     * @param Request The HTTP request.
     * @param MapInterface|ConfigurationFetcherInterface|array|string The map configuration.
     * @param array The fetcher parameters.
     * @param array The navigation data.
     * @param boolean Whether or not to do the navigation during creation.
     *
     * @return NavigatorInterface
     */
    public function createNavigator(
        Request $request,
        $configuration,
        array $parameters = array(),
        array $data = array(),
        $navigate = true
    );
}
