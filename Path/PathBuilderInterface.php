<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

interface PathBuilderInterface
{
    /**
     * Returns built path.
     *
     * @param string $type      The path type
     * @param array  $options   The path options
     *
     * @return PathInterface The step.
     */
    public function build($type, array $options = array());
}