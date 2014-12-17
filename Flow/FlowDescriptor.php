<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

class FlowDescriptor implements FlowDescriptorInterface
{
    /**
     * The taken paths.
     *
     * @var array
     */
    protected $takenPaths = array();

    /**
     * Add a taken path.
     *
     * @param string $name The identifier name of the step.
     */
    public function addTakenPath($name)
    {
        $this->takenPaths[] = $name;
    }

    /**
     * Retrace to a step.
     *
     * @param string $name The identifier name of the step.
     */
    public function retraceSteps($name)
    {
        $remove = false;

        foreach ($this->takenPaths as $i => $path) {
            if ($remove) {
                unset($this->takenPaths[$i]);
            } elseif ($path === $name) {
                $remove = true;
            }
        }
    }
}
