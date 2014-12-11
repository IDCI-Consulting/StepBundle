<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use IDCI\Bundle\StepBundle\Step\StepRegistryInterface;
use IDCI\Bundle\StepBundle\Path\PathRegistryInterface;

class MapFactory implements MapFactoryInterface
{
    /**
     * @var StepRegistry
     */
    private $stepRegistry;

    /**
     * @var PathRegistry
     */
    private $pathRegistry;

    /**
     * Constructor
     *
     * @param StepRegistryInterface $stepRegistry The registry of steps
     * @param PathRegistryInterface $pathRegistry The registry of paths
     */
    public function __construct(StepRegistryInterface $stepRegistry, PathRegistryInterface $pathRegistry)
    {
        $this->stepRegistry = $stepRegistry;
        $this->pathRegistry = $pathRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function createBuilder($data = null, array $options = array())
    {
        return $this->createNamedBuilder($name = 'map', $data, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function createNamedBuilder($name, $data = null, array $options = array())
    {
        return new MapBuilder(
            $name,
            $data,
            $options,
            $this->stepRegistry,
            $this->pathRegistry
        );
    }
}