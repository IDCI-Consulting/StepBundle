<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type\Configuration;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

class PathTypeConfigurationRegistry implements PathTypeConfigurationRegistryInterface
{
    /**
     * @var array
     */
    protected $configurations;

    /**
     * {@inheritDoc}
     */
    public function setConfiguration($alias, PathTypeConfigurationInterface $configuration)
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
                'Could not load path type configuration "%s". Available configurations are %s',
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
