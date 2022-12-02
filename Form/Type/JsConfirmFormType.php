<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JsConfirmFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['observed_id'] = sprintf(
            'idci_step_navigator__path_%s',
            $options['path_index']
        );

        $view->vars['message'] = $options['message'];
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return HiddenType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['path_index'])
            ->setDefaults(['message' => 'Are you sure ?'])
            ->setAllowedTypes('path_index', ['integer'])
            ->setAllowedTypes('message', ['string'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'idci_step_action_form_js_confirm';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return $this->getName();
    }
}
