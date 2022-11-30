<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\ConditionalRule;

use IDCI\Bundle\StepBundle\Exception;

interface ConditionalRuleRegistryInterface
{
    /**
     * Sets a conditional rule identify by a alias.
     */
    public function setRule(string $alias, ConditionalRuleInterface $rule): self;

    /**
     * Returns a conditional rule by alias.
     */
    public function getRule(string $alias): self;

    /**
     * Returns whether the given conditional rule is supported.
     */
    public function hasRule(string $alias): bool;
}
