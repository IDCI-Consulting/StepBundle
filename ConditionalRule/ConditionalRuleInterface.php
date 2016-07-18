<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\ConditionalRule;

interface ConditionalRuleInterface
{
    /**
     * Match
     *
     * @param array $options The options to match.
     *
     * @return boolean Return true if the conditional rule match.
     */
    public function match(array $options = array());
}
