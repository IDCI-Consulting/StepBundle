<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\ConditionalRule;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

class ConditionalRuleRegistry implements ConditionalRuleRegistryInterface
{
    /**
     * @var ConditionalRuleInterface[]
     */
    private $rules = array();

    /**
     * {@inheritdoc}
     */
    public function setRule($alias, ConditionalRuleInterface $rule)
    {
        $this->rules[$alias] = $rule;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRule($alias)
    {
        if (!is_string($alias)) {
            throw new UnexpectedTypeException($alias, 'string');
        }

        if (!isset($this->rules[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load conditional rule "%s"', $alias));
        }

        return $this->rules[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function hasRule($alias)
    {
        if (!isset($this->rules[$alias])) {
            return false;
        }

        return true;
    }
}
