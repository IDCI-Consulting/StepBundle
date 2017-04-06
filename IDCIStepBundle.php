<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle;

use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\StepTypeConfigurationCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\StepTypeCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\StepEventCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\PathTypeCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\PathEventActionCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\ConditionalRuleCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\FlowDataStoreCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\ConfigurationWorkerCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\ConfigurationFetcherCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\MergerEnvironmentCompilerPass;

class IDCIStepBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new StepTypeCompilerPass());
        $container->addCompilerPass(new StepTypeConfigurationCompilerPass());
        $container->addCompilerPass(new StepEventCompilerPass());
        $container->addCompilerPass(new PathTypeCompilerPass());
        $container->addCompilerPass(new PathEventActionCompilerPass());
        $container->addCompilerPass(new ConditionalRuleCompilerPass());
        $container->addCompilerPass(new FlowDataStoreCompilerPass());
        $container->addCompilerPass(new ConfigurationWorkerCompilerPass());
        $container->addCompilerPass(new ConfigurationFetcherCompilerPass());
        $container->addCompilerPass(new MergerEnvironmentCompilerPass());
    }
}
