<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use IDCI\Bundle\StepBundle\Form\Type\LinkFormType;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddLinkStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(StepEventInterface $event, array $parameters = [])
    {
        $form = $event->getForm();

        $form->add(
            uniqid('_link_'),
            LinkFormType::class,
            $parameters['link_options']
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'link_options' => [],
            ])
        ;
    }
}
