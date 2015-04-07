<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

use IDCI\Bundle\StepBundle\Step\StepInterface;

interface PathBuilderInterface
{
    /**
     * Returns built path.
     *
     * @param string $typeAlias The type alias.
     * @param array  $options   The options.
     * @param array  $steps     The map steps.
     *
     * @return PathInterface The step.
     */
    public function build($typeAlias, array $options = array(), array $steps);
}