<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class AddLinkStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(
        FormEvent $event,
        NavigatorInterface $navigator,
        $parameters = array(),
        $data = null
    )
    {
        $form = $event->getForm();

        $form->add(
            uniqid('_link_'),
            'idci_step_action_form_link',
            $parameters['link_options']
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'link_options' => array(),
            ))
        ;
    }
}