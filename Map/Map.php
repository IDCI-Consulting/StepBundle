<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Exception\StepNotFoundException;

class Map implements MapInterface
{
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
     * @param array $configuration the configuration
     */
    public function __construct(array $configuration = array())
    {
        $this->configuration = $configuration;
    }

    /**
     * Set the first step name.
     *
     * @param string $name the first step name
     *
     * @return MapInterface
     */
    public function setFirstStepName($name)
    {
        $this->configuration['options']['first_step_name'] = $name;

        return $this;
    }

    /**
     * Returns the first step name.
     *
     * @return string
     */
    public function getFirstStepName()
    {
        return $this->configuration['options']['first_step_name'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->configuration['name'];
    }

    /**
     * {@inheritdoc}
     */
    public function getFootprint()
    {
        return $this->configuration['footprint'];
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return isset($this->configuration['data']) ?
            $this->configuration['data'] :
            array()
        ;
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

        if (null !== $step->getData()) {
            // Merge step data with map data
            $configurationData = $this->getData();
            $this->configuration['data'][$name] = array_replace_recursive(
                isset($configurationData[$name]) ? $configurationData[$name] : array(),
                $step->getData()
            );
        }

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
            throw new StepNotFoundException($name, $this->getName());
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
    public function getFirstStep()
    {
        return $this->getStep($this->getFirstStepName());
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
    public function getFinalDestination()
    {
        return $this->configuration['options']['final_destination'];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormAction()
    {
        return $this->configuration['options']['form_action'];
    }

    /**
     * {@inheritdoc}
     */
    public function isDisplayStepInUrlEnabled()
    {
        return $this->configuration['options']['display_step_in_url'];
    }

    /**
     * {@inheritdoc}
     */
    public function isResetFlowDataOnInitEnabled()
    {
        return $this->configuration['options']['reset_flow_data_on_init'];
    }

    /**
     * {@inheritdoc}
     */
    public function __sleep()
    {
        return array('configuration');
    }
}
