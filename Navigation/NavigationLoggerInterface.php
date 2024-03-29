<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

interface NavigationLoggerInterface
{
    /**
     * Start navigation.
     */
    public function startNavigation();

    /**
     * Stop navigation.
     *
     * @param NavigatorInterface $navigator the navigator to log
     */
    public function stopNavigation(NavigatorInterface $navigator);

    /**
     * Has navigator.
     */
    public function hasNavigator(): bool;

    /**
     * Get navigation.
     */
    public function getNavigation(): ?array;
}
