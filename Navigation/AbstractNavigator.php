<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

abstract class AbstractNavigator implements NavigatorInterface 
{
    /**
     * Returns the navigator name
     *
     * @return string
     */
    public static function getName()
    {
        return 'idci_step_navigator';
    }
}