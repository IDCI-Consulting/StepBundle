<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
    }
}