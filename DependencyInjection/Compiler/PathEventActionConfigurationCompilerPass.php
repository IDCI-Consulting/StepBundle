<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use IDCI\Bundle\ExtraStepBundle\Exception\UndefinedServiceException;
use IDCI\Bundle\StepBundle\Path\Event\Configuration\PathEventActionConfigurationInterface;
use IDCI\Bundle\StepBundle\Path\Event\Configuration\PathEventActionConfigurationRegistryInterface;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This compiler pass loop over the yaml configuration under the idci_step.path_event_actions key
 * It creates a service for each configuration, and inject it in the configuration registry.
 */
class PathEventActionConfigurationCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(PathEventActionConfigurationRegistryInterface::class)) {
            return;
        }

        $registryDefinition = $container->findDefinition(PathEventActionConfigurationRegistryInterface::class);
        $pathEventActionsConfiguration = $container->getParameter('idci_step.path_event_actions');

        foreach ($pathEventActionsConfiguration as $configurationName => $configuration) {
            $serviceDefinition = new ChildDefinition(PathEventActionConfigurationInterface::class);

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
        return sprintf('idci_step.path_event_action_configuration.%s', $name);
    }
}
