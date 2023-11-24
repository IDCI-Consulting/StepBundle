<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Camille SCHWARZ <camille54460@gmail.com>
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
            ->add(sprintf('_js_confirm_%d', $event->getPathIndex()), JsConfirmFormType::class, [
                'path_index' => $event->getPathIndex(),
                'conditions' => $parameters['conditions'],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('conditions', function (OptionsResolver $conditionsResolver): void {
                $conditionsResolver
                    ->setPrototype(true)
                    ->setDefault('message', null)->setAllowedTypes('message', ['null', 'string'])
                    ->setDefault('observed_fields', null)->setAllowedTypes('observed_fields', ['null', 'array'])
                ;
            })
        ;
    }
}
