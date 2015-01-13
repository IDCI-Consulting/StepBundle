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
     * Navigate through map's steps following the chosen path.
     *
     * @throw LogicException if the navigation is executed twice.
     */
    public function navigate();

    /**
     * Get the map.
     *
     * @return MapInterface
     */
    public function getMap();

    /**
     * Get the flow.
     *
     * @return FlowInterface
     */
    public function getFlow();

    /**
     * Returns the current step.
     *
     * @return StepInterface
     */
    public function getCurrentStep();

    /**
     * Returns the previous step.
     *
     * @return StepInterface|null
     */
    public function getPreviousStep();

    /**
     * Returns the current step data.
     *
     * @return array|null
     */
    public function getCurrentStepData();

    /**
     * Set current step data.
     *
     * @param array $data The step data.
     */
    public function setCurrentStepData(array $data);

    /**
     * Returns the current normalized step data.
     *
     * @return array|null
     */
    public function getCurrentNormalizedStepData();

    /**
     * Returns the available paths.
     *
     * @return array
     */
    public function getAvailablePaths();

    /**
     * Returns the taken path.
     *
     * @return array
     */
    public function getTakenPaths();

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

    /**
     * Create step view.
     *
     * @return Symfony\Component\Form\FormView
     */
    public function createStepView();
}
