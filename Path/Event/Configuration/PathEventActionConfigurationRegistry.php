<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Configuration;

class PathEventActionConfigurationRegistry implements PathEventActionConfigurationRegistryInterface
{
    /**
     * @var array
     */
    protected $configurations;

    /**
     * {@inheritdoc}
     */
    public function setConfiguration(string $alias, PathEventActionConfigurationInterface $configuration): PathEventActionConfigurationRegistryInterface
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
    public function getConfiguration(string $alias): PathEventActionConfigurationInterface
    {
        if (!isset($this->configurations[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load path event action configuration "%s". Available configurations are %s', $alias, implode(', ', array_keys($this->configurations))));
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
