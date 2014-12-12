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
     *
     * @param string $type      The step type
     * @param array  $options   The step options
     *
     * @return StepInterface The step.
     */
    public function build($type, array $options = array());
}