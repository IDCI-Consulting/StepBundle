<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type\Configuration;

interface StepTypeConfigurationInterface
{
    /**
     * Returns the name.
     */
    public function getName(): string;

    /**
     * Returns the parent.
     */
    public function getParent(): self;

    /**
     * Returns the description.
     */
    public function getDescription(): string;

    /**
     * Is abstract.
     */
    public function isAbstract(): bool;

    /**
     * Returns extra form options.
     */
    public function getExtraFormOptions(): array;
}
