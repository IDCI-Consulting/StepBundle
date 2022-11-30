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
     */
    public function build(string $typeAlias, array $options = [], array $steps = []): PathInterface;
}
