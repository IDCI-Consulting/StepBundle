<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;

interface PathEventActionInterface
{
    /**
     * Execute action.
     *
     * @param PathEventInterface $event      The path event.
     * @param array              $parameters The parameters.
     */
    public function execute(
        PathEventInterface $event,
        array $parameters = array()
    );
}