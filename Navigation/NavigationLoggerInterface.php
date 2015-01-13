<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

interface NavigationLoggerInterface
{
    /**
     * Start navigation
     */
    public function startNavigation();

    /**
     * Stop navigation
     *
     * @param NavigatorInterface $navigator The navigator to log.
     */
    public function stopNavigation(NavigatorInterface $navigator);

    /**
     * Has navigator
     *
     * @return boolean
     */
    public function hasNavigator();

    /**
     * Get navigation
     *
     * @return array|null
     */
    public function getNavigation();
}