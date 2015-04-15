<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;

interface StepEventActionInterface
{
    /**
     * Execute action.
     *
     * @param StepEventInterface $event      The step event.
     * @param array              $parameters The parameters.
     */
    public function execute(
        StepEventInterface $event,
        array $parameters = array()
    );
}