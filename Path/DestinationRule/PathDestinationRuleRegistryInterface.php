<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\DestinationRule;

use IDCI\Bundle\StepBundle\Path\DestinationRule\PathDestinationRuleInterface;

interface PathDestinationRuleRegistryInterface
{
    /**
     * Sets a path destination rule identify by a alias.
     *
     * @param string                       $alias The rule alias.
     * @param PathDestinationRuleInterface $path  The rule.
     *
     * @return PathDestinationRuleRegistryInterface
     */
    public function setRule($alias, PathDestinationRuleInterface $rule);

    /**
     * Returns a path destination rule by alias.
     *
     * @param string $alias The alias of the rule.
     *
     * @return PathDestinationRuleInterface The rule
     *
     * @throws Exception\UnexpectedTypeException  if the passed alias is not a string.
     * @throws Exception\InvalidArgumentException if the rule can not be retrieved.
     */
    public function getRule($alias);

    /**
     * Returns whether the given path destination rule is supported.
     *
     * @param string $alias The alias of the rule.
     *
     * @return bool Whether the rule is supported.
     */
    public function hasRule($alias);
}
