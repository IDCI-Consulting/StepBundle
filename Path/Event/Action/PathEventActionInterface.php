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
     * @param PathEventInterface $event      the path event
     * @param array              $parameters the parameters
     *
     * @return mixed
     */
    public function execute(PathEventInterface $event, array $parameters = []);
}
