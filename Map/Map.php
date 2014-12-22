<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Map\View\MapView;
use IDCI\Bundle\StepBundle\Form\MapStepType;

class Map implements MapInterface
{
    /**
     * The identifier name of the map.
     *
     * @var string
     */
    protected $name;

    /**
     * The map navigator.
     *
     * @var MapNavigatorInterface
     */
    protected $mapNavigator;

    /**
     * The configuration.
     *
     * @var array
     */
    protected $configuration;

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
     * Constructor.
     *
     * @param string                $name          The name identifier.
     * @param MapNavigatorInterface $mapNavigator  The map navigator.
     * @param array                 $configuration The configuration.
     */
    public function __construct(
        $name,
        MapNavigatorInterface $mapNavigator,
        array $configuration = array()
    )
    {
        $this->name          = $name;
        $this->mapNavigator  = $mapNavigator;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

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
    public function countSteps()
    {
        return count($this->steps);
    }

    /**
     * {@inheritdoc}
     */
    public function getPaths($source = null)
    {
        if (null === $source) {
            return $this->paths;
        }

        return isset($this->paths[$source])
            ? $this->paths[$source]
            : array()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($source, $index)
    {
        return $this->paths[$source][$index];
    }

    /**
     * {@inheritdoc}
     */
    public function createStepView($stepName = null)
    {
        $formBuilder = $this
            ->mapNavigator
            ->createStepFormBuilder(new MapStepType($this, $stepName))
        ;

        return $formBuilder->getForm()->createView();
    }
}
