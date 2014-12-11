<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

interface MapFactoryInterface
{
    /**
     * Returns a map builder.
     *
     * @param mixed $data    The initial data
     * @param array $options The options
     *
     * @return MapBuilderInterface The map builder
     */
    public function createBuilder($data = null, array $options = array());

    /**
     * Returns a map builder.
     *
     * @param string    $name    The name of the map
     * @param mixed     $data    The initial data
     * @param array     $options The options
     *
     * @return MapBuilderInterface The map builder
     */
    public function createNamedBuilder($name, $data = null, array $options = array());
}
