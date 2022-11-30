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
     * @param StepEventInterface $event      the step event
     * @param array              $parameters the parameters
     *
     * @return mixed
     */
    public function execute(StepEventInterface $event, array $parameters = []);
}
