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
     * @param string $name      the name
     * @param string $typeAlias the type alias
     * @param array  $options   the options
     *
     * @return StepInterface the step
     */
    public function build($name, $typeAlias, array $options = array());
}
