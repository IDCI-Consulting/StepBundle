<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use IDCI\Bundle\StepBundle\Configuration\Fetcher\ConfigurationFetcherInterface;
use IDCI\Bundle\StepBundle\Configuration\Fetcher\ConfigurationFetcherRegistryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ChildDefinition;

class ConfigurationFetcherCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ConfigurationFetcherRegistryInterface::class)) {
            return;
        }

        $registryDefinition = $container->findDefinition(ConfigurationFetcherRegistryInterface::class);

        $configurations = $container->getParameter('idci_step.maps');
        foreach ($configurations as $name => $configuration) {
            $fetcherDefinition = new ChildDefinition(ConfigurationFetcherInterface::class);
            $fetcherServiceId = sprintf('idci_step.configuration.fetcher.%s', $name);

            $fetcherDefinition->replaceArgument(0, $configuration);
            $container->setDefinition($fetcherServiceId, $fetcherDefinition);

            $registryDefinition->addMethodCall(
                'setFetcher',
                array($name, new Reference($fetcherServiceId))
            );
        }

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
