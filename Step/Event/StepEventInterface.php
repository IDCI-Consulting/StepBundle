<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use Symfony\Component\Form\FormInterface;

interface StepEventInterface
{
    /**
     * Gets the event's name.
     */
    public function getName(): string;

    /**
     * Returns the navigator at the source of the event.
     */
    public function getNavigator(): NavigatorInterface;

    /**
     * Returns the form at the source of the event.
     */
    public function getForm(): FormInterface;

    /**
     * Returns the data associated with this event.
     *
     * @return mixed
     */
    public function getData();

    /**
     * Allows updating with some filtered data.
     *
     * @param mixed $data The data
     */
    public function setData($data);

    /**
     * Returns the data created by this event.
     *
     * @return mixed
     */
    public function getStepEventData();

    /**
     * Stops the propagation of the event to further step events.
     */
    public function stopPropagation();

    /**
     * Returns whether further step events should be triggered.
     */
    public function isPropagationStopped(): bool;
}
