<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle;

use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\ConditionalRuleCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\ConfigurationFetcherCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\ConfigurationWorkerCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\EnvironmentExtensionCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\FlowDataStoreCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\PathEventActionCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\PathEventActionConfigurationCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\PathTypeCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\PathTypeConfigurationCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\StepEventActionCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\StepEventActionConfigurationCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\StepTypeCompilerPass;
use IDCI\Bundle\StepBundle\DependencyInjection\Compiler\StepTypeConfigurationCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IDCIStepBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new StepTypeConfigurationCompilerPass());
        $container->addCompilerPass(new StepEventActionConfigurationCompilerPass());
        $container->addCompilerPass(new PathTypeConfigurationCompilerPass());
        $container->addCompilerPass(new PathEventActionConfigurationCompilerPass());
        $container->addCompilerPass(new StepTypeCompilerPass());
        $container->addCompilerPass(new StepEventActionCompilerPass());
        $container->addCompilerPass(new PathTypeCompilerPass());
        $container->addCompilerPass(new PathEventActionCompilerPass());
        $container->addCompilerPass(new ConditionalRuleCompilerPass());
        $container->addCompilerPass(new FlowDataStoreCompilerPass());
        $container->addCompilerPass(new ConfigurationWorkerCompilerPass());
        $container->addCompilerPass(new ConfigurationFetcherCompilerPass());
        $container->addCompilerPass(new EnvironmentExtensionCompilerPass());
    }
}
