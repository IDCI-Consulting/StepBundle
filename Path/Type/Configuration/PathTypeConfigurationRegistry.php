<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type\Configuration;

class PathTypeConfigurationRegistry implements PathTypeConfigurationRegistryInterface
{
    /**
     * @var array
     */
    protected $configurations;

    /**
     * {@inheritdoc}
     */
    public function setConfiguration(string $alias, PathTypeConfigurationInterface $configuration): PathTypeConfigurationRegistryInterface
    {
        $this->configurations[$alias] = $configuration;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurations(): array
    {
        return $this->configurations;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(string $alias): PathTypeConfigurationInterface
    {
        if (!isset($this->configurations[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load path type configuration "%s". Available configurations are %s', $alias, implode(', ', array_keys($this->configurations))));
        }

        return $this->configurations[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function hasConfiguration(string $alias): bool
    {
        if (!isset($this->configurations[$alias])) {
            return true;
        }

        return false;
    }
}
