<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration;

use IDCI\Bundle\StepBundle\Configuration\Worker\ConfigurationWorkerRegistryInterface;

class ConfigurationBuilder implements ConfigurationBuilderInterface
{
    /**
     * The configuration worker registry.
     *
     * @var ConfigurationWorkerRegistryInterface
     */
    protected $workerRegistry;

    /**
     * Constructor.
     *
     * @param ConfigurationWorkerRegistryInterface $workerRegistry The configuration worker registry.
     */
    public function __construct(ConfigurationWorkerRegistryInterface $workerRegistry)
    {
        $this->workerRegistry = $workerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function build(array $parameters = array())
    {
        // TODO: Implement.
    }
}