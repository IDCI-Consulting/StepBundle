<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Map\View\MapView;

class Map implements MapInterface
{
    /**
     * The steps.
     *
     * @var array
     */
    protected $steps = array();

    /**
     * The paths.
     *
     * @var array
     */
    protected $paths = array();

    /**
     * {@inheritdoc}
     */
    public function addStep($name, StepInterface $step)
    {
        $this->steps[$name] = $step;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addPath($name, PathInterface $path)
    {
        $this->paths[$name] = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasStep($name)
    {
        return isset($this->steps[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasPath($name)
    {
        return isset($this->paths[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getStep($name)
    {
        if (!$this->hasStep($name)) {
            throw new \LogicException(sprintf(
                'No step "%s" found.',
                $name
            ));
        }

        return $this->steps[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($name)
    {
        if (!$this->hasPath($name)) {
            throw new \LogicException(sprintf(
                'No path "%s" found.',
                $name
            ));
        }

        return $this->paths[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * {@inheritdoc}
     */
    public function createView()
    {
        return new MapView();
    }
}
