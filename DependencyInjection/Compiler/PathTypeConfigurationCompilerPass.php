<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use IDCI\Bundle\ExtraFormBundle\Exception\WrongExtraFormTypeOptionException;
use IDCI\Bundle\ExtraStepBundle\Exception\UndefinedServiceException;
use IDCI\Bundle\StepBundle\Path\Type\Configuration\PathTypeConfigurationInterface;
use IDCI\Bundle\StepBundle\Path\Type\Configuration\PathTypeConfigurationRegistryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ChildDefinition;

/**
 * This compiler pass loop over the yaml configuration under the idci_step.path_types key
 * It creates a service for each configuration, and inject it in the configuration registry.
 */
class PathTypeConfigurationCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(PathTypeConfigurationRegistryInterface::class)) {
            return;
        }

        $registryDefinition = $container->findDefinition(PathTypeConfigurationRegistryInterface::class);
        $pathTypesConfiguration = $container->getParameter('idci_step.path_types');
        $extraFormOptions = array();

        foreach ($pathTypesConfiguration as $configurationName => $configuration) {
            $serviceDefinition = new ChildDefinition(PathTypeConfigurationInterface::class);

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
                array($alias, new Reference($this->getDefinitionName($configurationName)))
            );

            $extraFormOptions[$configurationName] = $configuration['extra_form_options'];
        }

        // Check extra_form_options
        foreach ($extraFormOptions as $name => $options) {
            foreach ($options as $optionName => $optionValue) {
                if (!$container->hasDefinition(sprintf('idci_extra_form.type.%s', $optionValue['extra_form_type']))) {
                    throw new WrongExtraFormTypeOptionException(
                        $name,
                        $optionName,
                        sprintf('Undefined ExtraFormType "%s"', $optionValue['extra_form_type'])
                    );
                }
            }
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
        return sprintf('idci_step.path_type_configuration.%s', $name);
    }
}
