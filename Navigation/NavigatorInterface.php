<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use IDCI\Bundle\StepBundle\Path\PathInterface;

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
     * @param string|null $stepName The identifier name of the step.
     *
     * @throw LogicException if the navigation doesn't has a previous step.
     */
    public function goBack($stepName = null);

    /**
     * Get the request.
     *
     * @return \Symfony\Component\HttpFoundation\Request|null
     */
    public function getRequest();

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
     * Set the chosen path.
     *
     * @param PathInterface $path
     *
     * @return NavigatorInterface
     */
    public function setChosenPath(PathInterface $path);

    /**
     * Returns the chosen path.
     *
     * @return PathInterface|null
     */
    public function getChosenPath();

    /**
     * Returns the previous step.
     *
     * @param string|null $stepName The identifier name of the step.
     *
     * @return StepInterface|null
     */
    public function getPreviousStep($stepName = null);

    /**
     * Returns the current step data.
     *
     * @param string|null $type The data type (null, 'reminded' or 'retrieved').
     *
     * @return array|null
     */
    public function getCurrentStepData($type = null);

    /**
     * Set current step data.
     *
     * @param array       $data    The step data.
     * @param string|null $type    The data type (null, 'reminded' or 'retrieved').
     */
    public function setCurrentStepData(array $data, $type = null);

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
     * Serialize the navigation flow
     *
     * @return string
     */
    public function serialize();

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
