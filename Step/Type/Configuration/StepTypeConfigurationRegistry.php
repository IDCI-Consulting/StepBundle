<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type\Configuration;

class StepTypeConfigurationRegistry implements StepTypeConfigurationRegistryInterface
{
    /**
     * @var array
     */
    protected $configurations;

    /**
     * {@inheritdoc}
     */
    public function setConfiguration(string $alias, StepTypeConfigurationInterface $configuration): StepTypeConfigurationRegistryInterface
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
    public function getConfiguration(string $alias): StepTypeConfigurationInterface
    {
        if (!isset($this->configurations[$alias])) {
            throw new \InvalidArgumentException(sprintf('Could not load step type configuration "%s". Available configurations are %s', $alias, implode(', ', array_keys($this->configurations))));
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
