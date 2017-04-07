<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use IDCI\Bundle\StepBundle\Step\Type\StepTypeConfiguration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class StepTypeCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('idci_step.step_type.registry')) {
            return;
        }

        $registryDefinition = $container->getDefinition('idci_step.step_type.registry');
        foreach ($container->findTaggedServiceIds('idci_step.step_type') as $id => $tags) {
            foreach ($tags as $attributes) {
                $alias = isset($attributes['alias'])
                    ? $attributes['alias']
                    : $id
                ;

                $bundleConfiguration = $this->mergeConfigurations($container->getExtensionConfig('idci_step'));

                if (!$this->hasConfiguration($alias, $bundleConfiguration)) {
                    throw new \Exception(sprintf(
                        'The step type \'%s\' does not have a configuration.
                        You must configure the step type under the idci_step.step_types key',
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

    /**
     * Check if a step type has a configuration
     *
     * @param $alias
     * @param $configuration
     *
     * @return bool
     */
    private function hasConfiguration($alias, $configuration)
    {
        return isset($configuration['step_types'][$alias]);
    }

    /**
     * Merge all configurations under the idci_step key
     *
     * @param array $configurations
     *
     * @return array
     */
    private function mergeConfigurations(array $configurations)
    {
        $mergedConfiguration = array();
        foreach ($configurations as $configuration) {
            $mergedConfiguration = array_merge($mergedConfiguration, $configuration);
        }

        return $mergedConfiguration;
    }
}
