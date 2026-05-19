<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use IDCI\Bundle\StepBundle\Form\Type\GoNextFormType;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoNextStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('condition', true)->setAllowedTypes('condition', ['bool', 'string'])->setNormalizer('condition', function (Options $options, $value) {
                return (bool) $value;
            })
            ->setDefault('path_index', 1)->setAllowedTypes('path_index', ['int', 'string'])->setNormalizer('path_index', function (Options $options, $value) {
                return (int) $value;
            })
            ->setDefault('hide_next_button', false)->setAllowedTypes('hide_next_button', ['bool'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecute(StepEventInterface $event, array $parameters = [])
    {
        if (!$parameters['condition']) {
            return false;
        }

        $form = $event->getForm();

        $form->add('_go_next', GoNextFormType::class, [
            'path_index' => $parameters['path_index'],
            'hide_next_button' => $parameters['hide_next_button'],
        ]);

        return true;
    }
}
