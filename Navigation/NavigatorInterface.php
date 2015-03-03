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
     * Go back to the previous step.
     *
     * @throw LogicException if the navigation doesn't has a previous step.
     */
    public function goBack();

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
     * Returns the current paths.
     *
     * @return array
     */
    public function getCurrentPaths();

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
     * @param array $data    The step data.
     * @param array $mapping The step data form type mapping.
     */
    public function setCurrentStepData(array $data, array $mapping = array());

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
     * Returns true if the navigator has returned.
     *
     * @return boolean
     */
    public function hasReturned();

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

    /**
     * Returns the step form view.
     *
     * @return Symfony\Component\Form\FormView
     */
    public function getFormView();
}
