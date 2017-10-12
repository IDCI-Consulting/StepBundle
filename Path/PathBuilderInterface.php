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
     * @param string $typeAlias the type alias
     * @param array  $options   the options
     * @param array  $steps     the map steps
     *
     * @return PathInterface the step
     */
    public function build($typeAlias, array $options = array(), array $steps = array());
}
