<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step;

use IDCI\Bundle\StepBundle\Step\Type\StepTypeInterface;

interface StepInterface
{
    /**
     * Set the configuration options.
     */
    public function setOptions(array $options): self;

    /**
     * Get the configuration options.
     */
    public function getOptions(): array;

    /**
     * Returns the step name.
     */
    public function getName(): string;

    /**
     * Returns a boolean to indicate That this step was define as a first step.
     */
    public function isFirst(): bool;

    /**
     * Returns the step types used to construct the step.
     */
    public function getType(): StepTypeInterface;

    /**
     * Returns the step data.
     */
    public function getData(): ?array;

    /**
     * Returns the form pre step content.
     */
    public function getPreStepContent(): ?string;

    /**
     * Returns the step data type mapping.
     */
    public function getDataTypeMapping(): array;
}
