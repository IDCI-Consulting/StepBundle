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
     * Get the type of the step.
     *
     * @return string The type.
     */
    public function getType();

    /**
     * Get the options.
     *
     * @return array The options.
     */
    public function getOptions();

    /**
     * Create a view.
     *
     * @return IDCI\Bundle\StepBundle\Step\View\StepView The view.
     */
    public function createView();
}
