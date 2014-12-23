<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class DataStoreCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('idci_step.data_store.registry')) {
            return;
        }

        $registryDefinition = $container->getDefinition('idci_step.data_store.registry');
        foreach ($container->findTaggedServiceIds('idci_step.data_store') as $id => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $id
            ;

            $registryDefinition->addMethodCall(
                'set',
                array($alias, new Reference($id))
            );
        }
    }
}