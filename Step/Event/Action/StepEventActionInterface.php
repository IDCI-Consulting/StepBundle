<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\Form\FormInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

interface StepEventActionInterface
{
    /**
     * Execute action.
     *
     * @param FormInterface      $form       The form.
     * @param NavigatorInterface $navigator  The navigator.
     * @param string             $stepName   The step name.
     * @param array              $parameters The parameters.
     */
    public function execute(
        FormInterface $form,
        NavigatorInterface $navigator,
        $stepName,
        array $parameters = array()
    );
}