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
     *
     * @param array $data    the initial data
     * @param array $options the options
     *
     * @return MapBuilderInterface The map builder
     */
    public function createBuilder(array $data = array(), array $options = array());

    /**
     * Returns a map builder.
     *
     * @param string $name    the name of the map
     * @param array  $data    the initial data
     * @param array  $options the options
     *
     * @return MapBuilderInterface The map builder
     */
    public function createNamedBuilder($name, array $data = array(), array $options = array());
}
