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
     * Start init
     */
    public function startInit();

    /**
     * Stop init
     *
     * @param NavigatorInterface $navigator The navigator to log.
     */
    public function stopInit(NavigatorInterface $navigator);

    /**
     * Get navigation
     *
     * @return array
     */
    public function getNavigation();
}