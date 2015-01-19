<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ConfigurationFetcherCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('idci_step.configuration.fetcher_registry')) {
            return;
        }

        $registryDefinition = $container->getDefinition('idci_step.configuration.fetcher_registry');
        foreach ($container->findTaggedServiceIds('idci_step.configuration.fetcher') as $id => $tags) {
            foreach ($tags as $attributes) {
                $alias = isset($attributes['alias'])
                    ? $attributes['alias']
                    : $id
                ;

                $registryDefinition->addMethodCall(
                    'setFetcher',
                    array($alias, new Reference($id))
                );
            }
        }
    }
}