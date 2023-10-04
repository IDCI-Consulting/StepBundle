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
    private array $extensionsList;

    public function __construct(LoaderInterface $loader, $options = [])
    {
        if (isset($options['extensions'])) {
            $this->extensionsList = $options['extensions'];
            unset($options['extensions']);
        }

        $options['autoescape'] = false;

        parent::__construct($loader, $options);

        $this->loadExtensions();
    }

    public function loadExtensions(): void
    {
        foreach ($this->extensionsList as $extensionClassName) {
            $this->addExtension(new $extensionClassName);
        }
    }
}