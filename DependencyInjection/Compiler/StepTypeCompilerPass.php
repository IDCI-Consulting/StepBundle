<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use IDCI\Bundle\StepBundle\Step\Type\StepTypeRegistryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class StepTypeCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(StepTypeRegistryInterface::class)) {
            return;
        }

        $registryDefinition = $container->findDefinition(StepTypeRegistryInterface::class);
        foreach ($container->findTaggedServiceIds('idci_step.step_type') as $id => $tags) {
            foreach ($tags as $attributes) {
                $alias = isset($attributes['alias'])
                    ? $attributes['alias']
                    : $id
                ;

                $configurationService = sprintf('idci_step.step_type_configuration.%s', $alias);

                if (!$container->has($configurationService)) {
                    throw new \Exception(sprintf(
                        'The step type \'%s\' does not have a configuration. You must configure the step type under the idci_step.step_types key',
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
