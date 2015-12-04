<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\DestinationRule;

interface PathDestinationRuleInterface
{
    /**
     * Match
     *
     * @param array $options   The options to match.
     *
     * @return boolean Return true if the destination rule match.
     */
    public function match(array $options = array());
}