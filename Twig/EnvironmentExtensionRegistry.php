<?php

namespace IDCI\Bundle\StepBundle\Twig;

use Twig\Extension\ExtensionInterface;

class EnvironmentExtensionRegistry implements EnvironmentExtensionRegistryInterface
{
    private array $extensions = [];

    public function addExtension(string $alias, ExtensionInterface $extension): EnvironmentExtensionRegistry
    {
        $this->extensions[$alias] = $extension;

        return $this;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function getExtension(string $alias): ExtensionInterface
    {
        if (!$this->hasExtension($alias)) {
            throw new \InvalidArgumentException(sprintf('Could not load extension "%s". Available extensions are %s', $alias, implode(', ', array_keys($this->extensions))));
        }

        return $this->extensions[$alias];
    }

    public function hasExtension(string $alias): bool
    {
        return isset($this->extensions[$alias]);
    }
}
