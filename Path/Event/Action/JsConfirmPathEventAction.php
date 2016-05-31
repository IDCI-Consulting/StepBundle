<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;

class JsConfirmPathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(
        PathEventInterface $event,
        array $parameters = array()
    )
    {
        $form = $event->getForm();

        $form
            ->add('_js_confirm', 'idci_step_action_form_js_confirm', array_merge(
                array('message'    => $parameters['message']),
                array('path_index' => $event->getPathIndex())
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