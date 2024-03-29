<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class IDCIStepExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('idci_step.maps', $config['maps']);
        $container->setParameter('idci_step.twig_merger', $config['twig_merger']);
        $container->setParameter('idci_step.serialization.mapping', $config['serialization']['mapping']);
        $container->setParameter('idci_step.step_types', $config['step_types']);
        $container->setParameter('idci_step.path_types', $config['path_types']);
        $container->setParameter('idci_step.path_event_actions', $config['path_event_actions']);
        $container->setParameter('idci_step.step_event_actions', $config['step_event_actions']);
    }
}
