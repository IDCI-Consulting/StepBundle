<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step;

use IDCI\Bundle\StepBundle\Step\Type\StepTypeInterface;

interface StepInterface
{
    /**
     * Get the configuration.
     *
     * @return array the configuration
     */
    public function getConfiguration();

    /**
     * Set the configuration options.
     *
     * @param array $options the configuration options
     *
     * @return stepInterface
     */
    public function setOptions($options);

    /**
     * Get the configuration options.
     *
     * @return array the configuration options
     */
    public function getOptions();

    /**
     * Returns the step name.
     *
     * @return string the step name
     */
    public function getName();

    /**
     * Returns a boolean to indicate That this step was define as a first step.
     *
     * @return boolean
     */
    public function isFirst();

    /**
     * Returns the step types used to construct the step.
     *
     * @return StepTypeInterface the step's type
     */
    public function getType();

    /**
     * Returns the step data.
     *
     * @return array|null the step's data
     */
    public function getData();

    /**
     * Returns the form pre step content.
     *
     * @return string|null the content
     */
    public function getPreStepContent();

    /**
     * Returns the step data type mapping.
     *
     * @return array the data type mapping
     */
    public function getDataTypeMapping();
}
