<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use IDCI\Bundle\StepBundle\Configuration\Worker\ConfigurationWorkerRegistryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ConfigurationWorkerCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ConfigurationWorkerRegistryInterface::class)) {
            return;
        }

        $registryDefinition = $container->findDefinition(ConfigurationWorkerRegistryInterface::class);
        foreach ($container->findTaggedServiceIds('idci_step.configuration.worker') as $id => $tags) {
            foreach ($tags as $attributes) {
                $alias = isset($attributes['alias']) ? $attributes['alias'] : $id;

                $registryDefinition->addMethodCall('setWorker', [$alias, new Reference($id)]);
            }
        }
    }
}
