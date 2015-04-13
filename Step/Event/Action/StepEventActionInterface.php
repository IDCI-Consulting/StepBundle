<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\Form\FormEvent;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

interface StepEventActionInterface
{
    /**
     * Execute action.
     *
     * @param FormEvent          $event      The form event.
     * @param NavigatorInterface $navigator  The navigator.
     * @param array              $parameters The parameters.
     * @param mixed              $data       The retrieved event data.
     */
    public function execute(
        FormEvent $event,
        NavigatorInterface $navigator,
        array $parameters = array(),
        $data = null
    );
}