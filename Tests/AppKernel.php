<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [];

        if (in_array($this->getEnvironment(), ['test'])) {
            $bundles[] = new Symfony\Bundle\FrameworkBundle\FrameworkBundle();
            $bundles[] = new Symfony\Bundle\SecurityBundle\SecurityBundle();
            $bundles[] = new Symfony\Bundle\TwigBundle\TwigBundle();
            $bundles[] = new JMS\SerializerBundle\JMSSerializerBundle();
            $bundles[] = new IDCI\Bundle\StepBundle\IDCIStepBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }
}
