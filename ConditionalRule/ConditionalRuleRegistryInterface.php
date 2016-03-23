<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\ConditionalRule;

use IDCI\Bundle\StepBundle\ConditionalRule\ConditionalRuleInterface;

interface ConditionalRuleRegistryInterface
{
    /**
     * Sets a conditional rule identify by a alias.
     *
     * @param string                   $alias The rule alias.
     * @param ConditionalRuleInterface $path  The rule.
     *
     * @return ConditionalRuleRegistryInterface
     */
    public function setRule($alias, ConditionalRuleInterface $rule);

    /**
     * Returns a conditional rule by alias.
     *
     * @param string $alias The alias of the rule.
     *
     * @return ConditionalRuleInterface The rule
     *
     * @throws Exception\UnexpectedTypeException  if the passed alias is not a string.
     * @throws Exception\InvalidArgumentException if the rule can not be retrieved.
     */
    public function getRule($alias);

    /**
     * Returns whether the given conditional rule is supported.
     *
     * @param string $alias The alias of the rule.
     *
     * @return bool Whether the rule is supported.
     */
    public function hasRule($alias);
}
