<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

interface FlowDescriptorInterface
{
    /**
     * Add a done step.
     *
     * @param string $name The identifier name of the step.
     */
    public function addDoneStep($name);

    /**
     * Whether or not a step has been done.
     *
     * @param string $name The identifier name of the step.
     *
     * @return boolean True if the step has been done, false otherwise.
     */
    public function hasDoneStep($name);

    /**
     * Retrace to a done step.
     *
     * @param string $name The identifier name of the step.
     *
     * @return array The removed done steps.
     */
    public function retraceDoneStep($name);

    /**
     * Get the current step.
     *
     * @return string The identifier name of the step.
     */
    public function getCurrentStep();

    /**
     * Set the current step.
     *
     * @param string $name The identifier name of the step.
     */
    public function setCurrentStep($name);
}
