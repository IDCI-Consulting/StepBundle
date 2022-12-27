<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use IDCI\Bundle\ExtraStepBundle\Exception\UndefinedServiceException;
use IDCI\Bundle\StepBundle\Step\Type\Configuration\StepTypeConfigurationInterface;
use IDCI\Bundle\StepBundle\Step\Type\Configuration\StepTypeConfigurationRegistryInterface;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This compiler pass loop over the yaml configuration under the idci_step.step_types key
 * It creates a service for each configuration, and inject it in the configuration registry.
 */
class StepTypeConfigurationCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(StepTypeConfigurationRegistryInterface::class)) {
            return;
        }

        $registryDefinition = $container->findDefinition(StepTypeConfigurationRegistryInterface::class);
        $stepTypesConfiguration = $container->getParameter('idci_step.step_types');
        $extraFormOptions = [];

        foreach ($stepTypesConfiguration as $configurationName => $configuration) {
            $serviceDefinition = new ChildDefinition(StepTypeConfigurationInterface::class);

            if (null !== $configuration['parent']) {
                if (!$container->hasDefinition($this->getDefinitionName($configuration['parent']))) {
                    throw new UndefinedServiceException($configuration['parent']);
                }

                $configuration['parent'] = new Reference(
                    $this->getDefinitionName($configuration['parent'])
                );
            }

            $configuration['name'] = $configurationName;
            $alias = $configurationName.'_configuration';

            $serviceDefinition->setAbstract(false);
            $serviceDefinition->setPublic(!$configuration['abstract']);
            $serviceDefinition->replaceArgument(0, $configuration);

            $container->setDefinition(
                $this->getDefinitionName($configurationName),
                $serviceDefinition
            );

            $registryDefinition->addMethodCall(
                'setConfiguration',
                [$alias, new Reference($this->getDefinitionName($configurationName))]
            );
        }
    }

    /**
     * Get definition name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getDefinitionName($name)
    {
        return sprintf('idci_step.step_type_configuration.%s', $name);
    }
}
