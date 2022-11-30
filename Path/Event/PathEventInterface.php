<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use Symfony\Component\Form\FormInterface;

interface PathEventInterface
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
    public function getPathEventData();

    /**
     * Returns the current step path index selected.
     */
    public function getPathIndex(): int;

    /**
     * Stops the propagation of the event to further path events.
     */
    public function stopPropagation();

    /**
     * Returns whether further path events should be triggered.
     */
    public function isPropagationStopped(): bool;
}
