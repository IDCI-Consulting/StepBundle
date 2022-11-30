<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use IDCI\Bundle\StepBundle\ConditionalRule\ConditionalRuleRegistryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ConditionalRuleCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ConditionalRuleRegistryInterface::class)) {
            return;
        }

        $registryDefinition = $container->findDefinition(ConditionalRuleRegistryInterface::class);
        foreach ($container->findTaggedServiceIds('idci_step.conditional_rule') as $id => $tags) {
            foreach ($tags as $attributes) {
                $alias = isset($attributes['alias']) ? $attributes['alias'] : $id;

                $registryDefinition->addMethodCall('setRule', [$alias, new Reference($id)]);
            }
        }
    }
}
