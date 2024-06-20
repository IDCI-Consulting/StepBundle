<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Twig;

use Twig\Environment as TwigEnvironment;
use Twig\Loader\LoaderInterface;

class Environment extends TwigEnvironment
{
    private EnvironmentExtensionRegistryInterface $environmentExtensionRegistry;

    public function __construct(
        LoaderInterface $loader,
        EnvironmentExtensionRegistryInterface $environmentExtensionRegistry,
        $options = []
    ) {
        $this->environmentExtensionRegistry = $environmentExtensionRegistry;

        $options['autoescape'] = false;
        parent::__construct($loader, $options);

        $this->loadExtensions();
    }

    public function loadExtensions(): void
    {
        foreach ($this->environmentExtensionRegistry->getExtensions() as $extension) {
            $this->addExtension($extension);
        }
    }
}
