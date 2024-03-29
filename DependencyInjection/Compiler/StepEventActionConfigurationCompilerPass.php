<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use IDCI\Bundle\ExtraStepBundle\Exception\UndefinedServiceException;
use IDCI\Bundle\StepBundle\Step\Event\Configuration\StepEventActionConfigurationInterface;
use IDCI\Bundle\StepBundle\Step\Event\Configuration\StepEventActionConfigurationRegistryInterface;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This compiler pass loop over the yaml configuration under the idci_step.step_event_actions key
 * It creates a service for each configuration, and inject it in the configuration registry.
 */
class StepEventActionConfigurationCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(StepEventActionConfigurationRegistryInterface::class)) {
            return;
        }

        $registryDefinition = $container->findDefinition(StepEventActionConfigurationRegistryInterface::class);
        $stepEventActionsConfiguration = $container->getParameter('idci_step.step_event_actions');
        $extraFormOptions = [];

        foreach ($stepEventActionsConfiguration as $configurationName => $configuration) {
            $serviceDefinition = new ChildDefinition(StepEventActionConfigurationInterface::class);

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
        return sprintf('idci_step.step_event_action_configuration.%s', $name);
    }
}
