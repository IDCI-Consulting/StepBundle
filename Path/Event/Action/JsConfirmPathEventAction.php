<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use IDCI\Bundle\StepBundle\Form\Type\JsConfirmFormType;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JsConfirmPathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(PathEventInterface $event, array $parameters = [])
    {
        $form = $event->getForm();

        $form
            ->add('_js_confirm', JsConfirmFormType::class, [
                'message' => $parameters['message'],
                'path_index' => $event->getPathIndex(),
                'observed_fields' => $parameters['observed_fields'],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('message', null)->setAllowedTypes('message', ['null', 'string'])
            ->setDefault('observed_fields', null)->setAllowedTypes('observed_fields', ['null', 'array'])
        ;
    }
}
