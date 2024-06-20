<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Twig;

use Twig\Extension\ExtensionInterface;

interface EnvironmentExtensionRegistryInterface
{
    /**
     * Add an extension identified by a alias.
     */
    public function addExtension(string $alias, ExtensionInterface $extension): self;

    /**
     * Returns all extensions.
     */
    public function getExtensions(): array;

    /**
     * Returns an extension by alias.
     */
    public function getExtension(string $alias): ExtensionInterface;

    /**
     * Returns whether the given extension is supported.
     */
    public function hasExtension(string $alias): bool;
}
