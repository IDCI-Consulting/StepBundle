<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FlowDataStoreCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('idci_step.flow.data_store_registry')) {
            return;
        }

        $registryDefinition = $container->findDefinition('idci_step.flow.data_store_registry');
        foreach ($container->findTaggedServiceIds('idci_step.flow.data_store') as $id => $tags) {
            foreach ($tags as $attributes) {
                $alias = isset($attributes['alias']) ? $attributes['alias'] : $id;

                $registryDefinition->addMethodCall('setStore', [$alias, new Reference($id)]);
            }
        }
    }
}
