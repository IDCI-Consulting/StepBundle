<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

interface NavigatorInterface
{
    /**
     * Get the map
     *
     * @return MapInterface
     */
    public function getMap();

    /**
     * Get the flow
     *
     * @return FlowInterface
     */
    public function getFlow();

    /**
     * Create step view
     *
     * @return Symfony\Component\Form\FormView
     */
    public function createStepView();

    /**
     * Returns the position name
     *
     * @return string
     */
    public function getPositionName();

    /**
     * Returns the current step
     *
     * @return StepInterface
     */
    public function getCurrentStep();

    /**
     * Returns the available paths
     *
     * @return array
     */
    public function getAvailablePaths();
}