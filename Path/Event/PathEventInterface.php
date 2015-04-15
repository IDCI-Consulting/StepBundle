<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event;


interface PathEventInterface
{
    /**
     * Gets the event's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the navigator at the source of the event.
     *
     * @return NavigatorInterface
     */
    public function getNavigator();

    /**
     * Returns the form at the source of the event.
     *
     * @return FormInterface
     */
    public function getForm();

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
     *
     * @return integer
     */
    public function getPathIndex();

    /**
     * Stops the propagation of the event to further path events.
     */
    public function stopPropagation();

    /**
     * Returns whether further path events should be triggered.
     *
     * @return boolean
     */
    public function isPropagationStopped();
}