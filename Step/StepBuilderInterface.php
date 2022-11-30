<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step;

interface StepBuilderInterface
{
    /**
     * Returns built step.
     */
    public function build(string $name, string $typeAlias, array $options = []): StepInterface;
}
