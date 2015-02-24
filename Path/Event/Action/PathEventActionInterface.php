<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\Form\FormInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

interface PathEventActionInterface
{
    /**
     * Execute action.
     *
     * @param FormInterface      $form       The form.
     * @param NavigatorInterface $navigator  The navigator.
     * @param integer            $pathIndex  The path index.
     * @param array              $parameters The parameters.
     */
    public function execute(
        FormInterface $form,
        NavigatorInterface $navigator,
        $index,
        array $parameters = array()
    );
}