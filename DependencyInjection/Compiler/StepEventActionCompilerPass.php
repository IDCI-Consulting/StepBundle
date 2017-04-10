<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class StepEventActionCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('idci_step.step_event_action.registry')) {
            return;
        }

        $registryDefinition = $container->getDefinition('idci_step.step_event_action.registry');
        foreach ($container->findTaggedServiceIds('idci_step.step_event_action') as $id => $tags) {
            foreach ($tags as $attributes) {
                $alias = isset($attributes['alias'])
                    ? $attributes['alias']
                    : $id
                ;

                $configurationService = sprintf('idci_step.step_event_action_configuration.%s', $alias);

                if (!$container->has($configurationService)) {
                    throw new \Exception(sprintf(
                        'The step event action \'%s\' does not have a configuration.
                        You must configure the step event action under the idci_step.step_event_actions key',
                        $alias
                    ));
                }

                $registryDefinition->addMethodCall(
                    'setAction',
                    array($alias, new Reference($id))
                );
            }
        }
    }
}
