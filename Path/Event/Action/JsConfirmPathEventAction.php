<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class JsConfirmPathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(
        FormEvent $event,
        NavigatorInterface $navigator,
        $pathIndex,
        $parameters = array(),
        $data = null
    )
    {
        $form = $event->getForm();

        $form
            ->add('_js_confirm', 'idci_step_action_form_js_confirm', array_merge(
                $parameters,
                array('path_index' => $pathIndex)
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('message'));
    }
}