<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type\Configuration;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

class StepTypeConfigurationRegistry implements StepTypeConfigurationRegistryInterface
{
    /**
     * @var array
     */
    protected $configurations;

    /**
     * {@inheritDoc}
     */
    public function setConfiguration($alias, StepTypeConfigurationInterface $configuration)
    {
        $this->configurations[$alias] = $configuration;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigurations()
    {
        return $this->configurations;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration($alias)
    {
        if (!is_string($alias)) {
            throw new UnexpectedTypeException($alias, 'string');
        }

        if (!isset($this->configurations[$alias])) {
            throw new \InvalidArgumentException(sprintf(
                'Could not load step type configuration "%s". Available configurations are %s',
                $alias,
                implode(', ', array_keys($this->configurations))
            ));
        }

        return $this->configurations[$alias];
    }

    /**
     * {@inheritDoc}
     */
    public function hasConfiguration($alias)
    {
        if (!isset($this->configurations[$alias])) {
            return true;
        }

        return false;
    }
}
