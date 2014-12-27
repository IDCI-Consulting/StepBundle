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
     * Returns the current step
     *
     * @return StepInterface
     */
    public function getCurrentStep();

    /**
     * Returns the previous step
     *
     * @return StepInterface|null
     */
    public function getPreviousStep();

    /**
     * Returns the available paths
     *
     * @return array
     */
    public function getAvailablePaths();

    /**
     * Returns true if the navigator has navigated.
     *
     * @return boolean
     */
    public function hasNavigated();

    /**
     * Returns true if the navigator has finished the navigation.
     *
     * @return boolean
     */
    public function hasFinished();

    /**
     * Save the navigation.
     */
    public function save();

    /**
     * Clear the navigation.
     */
    public function clear();
}