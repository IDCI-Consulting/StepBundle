<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use JMS\SerializerBundle\DependencyInjection\Compiler\ServiceMapPass;
use JMS\DiExtraBundle\DependencyInjection\Compiler\LazyServiceMapPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\StepCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\PathCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\DataStoreCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\FlowListenerCompilerPass;

class IDCIStepBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new StepCompilerPass());
        $container->addCompilerPass(new PathCompilerPass());
        $container->addCompilerPass(new DataStoreCompilerPass());
        $container->addCompilerPass(new FlowListenerCompilerPass());

        $container->addCompilerPass($this->getServiceMapPass('jms_serializer.serialization_visitor', 'format',
            function(ContainerBuilder $builder, Definition $def) {
                $builder
                    ->getDefinition('idci_step.serialization.serializer_provider')
                    ->replaceArgument(3, $def)
                ;
            }
        ));
        $container->addCompilerPass($this->getServiceMapPass('jms_serializer.deserialization_visitor', 'format',
            function(ContainerBuilder $builder, Definition $def) {
                $builder
                    ->getDefinition('idci_step.serialization.serializer_provider')
                    ->replaceArgument(4, $def)
                ;
            }
        ));
    }

    private function getServiceMapPass($tagName, $keyAttributeName, $callable)
    {
        if (class_exists('JMS\DiExtraBundle\DependencyInjection\Compiler\LazyServiceMapPass')) {
            return new LazyServiceMapPass($tagName, $keyAttributeName, $callable);
        }

        return new ServiceMapPass($tagName, $keyAttributeName, $callable);
    }
}