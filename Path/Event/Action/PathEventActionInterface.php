<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\Form\FormEvent;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

interface PathEventActionInterface
{
    /**
     * Execute action.
     *
     * @param FormEvent          $event      The form event.
     * @param NavigatorInterface $navigator  The navigator.
     * @param integer            $pathIndex  The path index.
     * @param array              $parameters The parameters.
     * @param mixed              $data       The retrieved event data.
     */
    public function execute(
        FormEvent $event,
        NavigatorInterface $navigator,
        $pathIndex,
        array $parameters = array(),
        $data = null
    );
}