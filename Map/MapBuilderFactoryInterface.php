<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

interface MapBuilderFactoryInterface
{
    /**
     * Returns a map builder.
     */
    public function createBuilder(array $data = [], array $options = []): MapBuilderInterface;

    /**
     * Returns a map builder.
     */
    public function createNamedBuilder(string $name, array $data = [], array $options = []): MapBuilderInterface;
}
