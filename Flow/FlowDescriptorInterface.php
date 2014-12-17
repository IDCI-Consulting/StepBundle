<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

interface FlowDescriptorInterface
{
    /**
     * Add a taken path.
     *
     * @param string $name The identifier name of the step.
     */
    public function addTakenPath($name);

    /**
     * Retrace to a step.
     *
     * @param string $name The identifier name of the step.
     */
    public function retraceSteps($name);
}
