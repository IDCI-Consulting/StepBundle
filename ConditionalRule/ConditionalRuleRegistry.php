<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\ConditionalRule;

class ConditionalRuleRegistry implements ConditionalRuleRegistryInterface
{
    /**
     * @var ConditionalRuleInterface[]
     */
    private $rules = [];

    /**
     * {@inheritdoc}
     */
    public function setRule(string $alias, ConditionalRuleInterface $rule): ConditionalRuleRegistryInterface
    {
        $this->rules[$alias] = $rule;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRule(string $alias): ConditionalRuleRegistryInterface
    {
        if (!isset($this->rules[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load conditional rule "%s"', $alias));
        }

        return $this->rules[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function hasRule(string $alias): bool
    {
        if (!isset($this->rules[$alias])) {
            return false;
        }

        return true;
    }
}
