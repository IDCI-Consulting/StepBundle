<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Configuration;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

class StepEventActionConfigurationRegistry implements StepEventActionConfigurationRegistryInterface
{
    /**
     * @var array
     */
    protected $configurations;

    /**
     * {@inheritdoc}
     */
    public function setConfiguration($alias, StepEventActionConfigurationInterface $configuration)
    {
        $this->configurations[$alias] = $configuration;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurations()
    {
        return $this->configurations;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration($alias)
    {
        if (!is_string($alias)) {
            throw new UnexpectedTypeException($alias, 'string');
        }

        if (!isset($this->configurations[$alias])) {
            throw new \InvalidArgumentException(sprintf(
                'Could not load step event action configuration "%s". Available configurations are %s',
                $alias,
                implode(', ', array_keys($this->configurations))
            ));
        }

        return $this->configurations[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function hasConfiguration($alias)
    {
        if (!isset($this->configurations[$alias])) {
            return true;
        }

        return false;
    }
}
