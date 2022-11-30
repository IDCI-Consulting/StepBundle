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

class GoNextFormType extends AbstractType
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
        $view->vars['to_click_next_button_id'] = sprintf(
            'idci_step_navigator__path_%s',
            $options['path_index']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
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
            ->setAllowedTypes('path_index', ['integer'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idci_step_action_form_go_next';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return $this->getName();
    }
}
