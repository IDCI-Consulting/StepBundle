<?php

namespace IDCI\Bundle\StepBundle\DependencyInjection\Compiler;

use IDCI\Bundle\StepBundle\Twig\EnvironmentExtensionRegistryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnvironmentExtensionCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $mergerConfiguration = $container->getParameter('idci_step.twig_merger');

        if (!isset($mergerConfiguration['extensions']) || !$container->has(EnvironmentExtensionRegistryInterface::class)) {
            return;
        }

        $registryDefinition = $container->findDefinition(EnvironmentExtensionRegistryInterface::class);

        foreach ($mergerConfiguration['extensions'] as $extensionAlias) {
            if (!$container->hasDefinition($extensionAlias)) {
                continue;
            }

            $registryDefinition->addMethodCall('addExtension', [$extensionAlias, $container->getDefinition($extensionAlias)]);
        }
    }
}
