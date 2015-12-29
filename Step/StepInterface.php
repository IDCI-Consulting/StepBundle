<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step;

use IDCI\Bundle\StepBundle\Step\StepInterface;

interface StepInterface
{
    /**
     * Get the configuration.
     *
     * @return array The configuration.
     */
    public function getConfiguration();

    /**
     * Set the configuration options.
     *
     * @param array $options The configuration options.
     *
     * @return StepInterface.
     */
    public function setOptions($options);

    /**
     * Get the configuration options.
     *
     * @return array The configuration options.
     */
    public function getOptions();

    /**
     * Returns the step name.
     *
     * @return string The step name.
     */
    public function getName();

    /**
     * Returns a boolean to indicate That this step was define as a first step
     *
     * @return boolean.
     */
    public function isFirst();

    /**
     * Returns the step types used to construct the step.
     *
     * @return StepTypeInterface The step's type.
     */
    public function getType();

    /**
     * Returns the step data.
     *
     * @return array|null The step's data.
     */
    public function getData();

    /**
     * Returns the form pre step content.
     *
     * @return string|null The content.
     */
    public function getPreStepContent();

    /**
     * Returns the step data type mapping.
     *
     * @return array The data type mapping.
     */
    public function getDataTypeMapping();
}
