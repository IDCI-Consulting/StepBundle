<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Step\StepInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

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
     * @param string|null $stepName the identifier name of the step
     *
     * @throw LogicException if the navigation doesn't has a previous step.
     */
    public function goBack(string $stepName = null);

    /**
     * Get the request.
     */
    public function getRequest(): ?Request;

    /**
     * Get the map.
     */
    public function getMap(): MapInterface;

    /**
     * Get the navigation data.
     */
    public function getData(): array;

    /**
     * Get the flow.
     */
    public function getFlow(): FlowInterface;

    /**
     * Get the flow recorder.
     */
    public function getFlowRecorder(): FlowRecorderInterface;

    /**
     * Returns the current step.
     */
    public function getCurrentStep(): StepInterface;

    /**
     * Returns the current paths.
     */
    public function getCurrentPaths(): array;

    /**
     * Set the chosen path.
     */
    public function setChosenPath(PathInterface $path): self;

    /**
     * Returns the chosen path.
     */
    public function getChosenPath(): ?PathInterface;

    /**
     * Returns the previous step.
     */
    public function getPreviousStep(string $stepName = null): ?StepInterface;

    /**
     * Add URL query parameter.
     *
     * @param string $key   the URL query parameter key
     * @param mixed  $value the URL query parameter value
     */
    public function addUrlQueryParameter(string $key, $value = null): self;

    /**
     * Returns the URL query parameters.
     */
    public function getUrlQueryParameters(): array;

    /**
     * Returns true if contains url query parameters.
     */
    public function hasUrlQueryParameters(): bool;

    /**
     * Set the final destination.
     */
    public function setFinalDestination(string $url = null): self;

    /**
     * Returns true if the navigator has a final destination.
     */
    public function hasFinalDestination(): bool;

    /**
     * Returns the final destination (as URL).
     */
    public function getFinalDestination(): ?string;

    /**
     * Set current step data.
     */
    public function setCurrentStepData(array $data, string $type = null);

    /**
     * Returns the current step data.
     */
    public function getCurrentStepData(string $type = null): ?array;

    /**
     * Returns the available paths.
     */
    public function getAvailablePaths(): array;

    /**
     * Returns the taken path.
     */
    public function getTakenPaths(): array;

    /**
     * Returns true if the navigator has navigated.
     */
    public function hasNavigated(): bool;

    /**
     * Returns true if the navigator has returned.
     */
    public function hasReturned(): bool;

    /**
     * Returns true if the navigator has finished the navigation.
     */
    public function hasFinished(): bool;

    /**
     * Serialize the navigation flow.
     */
    public function serialize(): string;

    /**
     * Save the navigation.
     */
    public function save();

    /**
     * Clear the navigation.
     */
    public function clear();

    /**
     * Stop the navigation.
     */
    public function stop();

    /**
     * Create step view.
     */
    public function createStepView(): FormView;

    /**
     * Returns the step form view.
     */
    public function getFormView(): FormView;
}
