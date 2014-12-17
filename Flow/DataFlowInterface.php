<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

interface DataFlowInterface
{
    /**
     * Whether or not it exists a data for a step.
     *
     * @param string $name The identifier name of the step.
     *
     * @return boolean True if it exists a data for the step, false otherwise.
     */
    public function hasStep($name);

    /**
     * Get the data of a step.
     *
     * @param string $name The identifier name of the step.
     *
     * @return array The associated data.
     *
     * @throws \LogicException if there is no associated data for the step.
     */
    public function getStep($name);

    /**
     * Set the data of a step.
     *
     * @param string $name The identifier name of the step.
     * @param array  $data The associated data.
     */
    public function setStep($name, array $data);
}
