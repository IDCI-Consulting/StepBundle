<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\DestinationRule;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

interface PathDestinationRuleInterface
{
    /**
     * Match
     *
     * @param array              $options   The options to match.
     * @param NavigatorInterface $navigator The navigator.
     *
     * @return boolean Return true if the destination rule match.
     */
    public function match(array $options = array(), NavigatorInterface $navigator);
}