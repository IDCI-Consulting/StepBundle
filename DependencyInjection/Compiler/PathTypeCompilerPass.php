<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class PathTypeCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('idci_step.path_type.registry')) {
            return;
        }

        $registryDefinition = $container->getDefinition('idci_step.path_type.registry');
        foreach ($container->findTaggedServiceIds('idci_step.path_type') as $id => $tags) {
            foreach ($tags as $attributes) {
                $alias = isset($attributes['alias'])
                    ? $attributes['alias']
                    : $id
                ;

                $configurationService = sprintf('idci_step.path_type_configuration.%s', $alias);

                if (!$container->has($configurationService)) {
                    throw new \Exception(sprintf(
                        'The path type \'%s\' does not have a configuration.
                        You must configure the path type under the idci_step.path_types key',
                        $alias
                    ));
                }

                $registryDefinition->addMethodCall(
                    'setType',
                    array($alias, new Reference($id))
                );
            }
        }
    }
}