<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\DestinationRule;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;
use IDCI\Bundle\StepBundle\Path\DestinationRule\PathDestinationRuleInterface;

class PathDestinationRuleRegistry implements PathDestinationRuleRegistryInterface
{
    /**
     * @var PathDestinationRuleInterface[]
     */
    private $rules = array();

    /**
     * {@inheritdoc}
     */
    public function setRule($alias, PathDestinationRuleInterface $rule)
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
            throw new \InvalidArgumentException(sprintf('Could not load path destination rule "%s"', $alias));
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
