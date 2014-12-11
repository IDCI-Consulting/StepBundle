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
    public function addPath($source, PathInterface $path)
    {
        if (!isset($this->paths[$source])) {
            $this->paths[$source] = array();
        }

        $this->paths[$source][] = $path;

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
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaths($source)
    {
        return isset($this->paths[$source])
            ? $this->paths[$source]
            : array()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function createView()
    {
        return new MapView();
    }
}
