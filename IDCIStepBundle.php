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
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\FlowDataStoreCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\ConfigurationWorkerCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\ConfigurationFetcherCompilerPass;

class IDCIStepBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new StepCompilerPass());
        $container->addCompilerPass(new PathCompilerPass());
        $container->addCompilerPass(new FlowDataStoreCompilerPass());
        $container->addCompilerPass(new ConfigurationWorkerCompilerPass());
        $container->addCompilerPass(new ConfigurationFetcherCompilerPass());
    }
}