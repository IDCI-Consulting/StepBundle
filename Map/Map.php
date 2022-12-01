<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use IDCI\Bundle\StepBundle\Exception\StepNotFoundException;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Step\StepInterface;

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
    protected $steps = [];

    /**
     * The paths.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * Constructor.
     *
     * @param array $configuration the configuration
     */
    public function __construct(array $configuration = [])
    {
        $this->configuration = $configuration;
    }

    /**
     * Set the first step name.
     */
    public function setFirstStepName(string $name): MapInterface
    {
        $this->configuration['options']['first_step_name'] = $name;

        return $this;
    }

    /**
     * Returns the first step name.
     */
    public function getFirstStepName(): ?string
    {
        return $this->configuration['options']['first_step_name'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->configuration['name'];
    }

    /**
     * {@inheritdoc}
     */
    public function getFootprint(): string
    {
        return $this->configuration['footprint'];
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return isset($this->configuration['data']) ?
            $this->configuration['data'] :
            []
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function addStep(string $name, StepInterface $step): MapInterface
    {
        $this->steps[$name] = $step;

        if (null !== $step->getData()) {
            // Merge step data with map data
            $configurationData = $this->getData();
            $this->configuration['data'][$name] = array_replace_recursive(
                isset($configurationData[$name]) ? $configurationData[$name] : [],
                $step->getData()
            );
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addPath(string $source, PathInterface $path): MapInterface
    {
        if (!isset($this->paths[$source])) {
            $this->paths[$source] = [];
        }

        $this->paths[$source][] = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasStep(string $name): bool
    {
        return isset($this->steps[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getStep(string $name): StepInterface
    {
        if (!$this->hasStep($name)) {
            throw new StepNotFoundException($name, $this->getName());
        }

        return $this->steps[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    /**
     * {@inheritdoc}
     */
    public function countSteps(): int
    {
        return count($this->steps);
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstStep(): ?StepInterface
    {
        return $this->getStep($this->getFirstStepName());
    }

    /**
     * {@inheritdoc}
     */
    public function getPaths(string $source = null): array
    {
        if (null === $source) {
            return $this->paths;
        }

        return isset($this->paths[$source])
            ? $this->paths[$source]
            : []
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(string $source, int $index): PathInterface
    {
        return $this->paths[$source][$index];
    }

    /**
     * {@inheritdoc}
     */
    public function getFinalDestination(): ?string
    {
        return $this->configuration['options']['final_destination'];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormAction(): ?string
    {
        return $this->configuration['options']['form_action'];
    }

    /**
     * {@inheritdoc}
     */
    public function isDisplayStepInUrlEnabled(): bool
    {
        return $this->configuration['options']['display_step_in_url'];
    }

    /**
     * {@inheritdoc}
     */
    public function isResetFlowDataOnInitEnabled(): bool
    {
        return $this->configuration['options']['reset_flow_data_on_init'];
    }

    /**
     * {@inheritdoc}
     */
    public function __sleep()
    {
        return ['configuration'];
    }
}
