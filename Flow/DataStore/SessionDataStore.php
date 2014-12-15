<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow\DataStore;

class SessionDataStore implements FlowDataStoreInterface
{
    /**
     * Save.
     *
     * @param string        $name The identifier name of the step.
     * @param StepInterface $step The step.
     *
     * @return MapInterface This.
     */
    public function save($name, StepInterface $step);

    /**
     * Retrieve.
     *
     * @param string        $name The identifier name of the step.
     * @param StepInterface $step The step.
     *
     * @return MapInterface This.
     */
    public function retrieve($name, StepInterface $step);
}
