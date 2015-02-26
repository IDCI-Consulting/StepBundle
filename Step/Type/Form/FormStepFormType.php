<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormStepFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge($view->vars, array(
            'display_title' => $options['display_title'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['builder']->all() as $fieldName => $fieldBuilder) {
            $fieldOptions = $fieldBuilder->getOptions();

            if (isset($options['data'][$fieldName])) {
                $fieldOptions['data'] = $options['data'][$fieldName];
            }

            $builder->add(
                $fieldName,
                $fieldBuilder->getType()->getInnerType(),
                $fieldOptions
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('builder'))
            ->setDefaults(array(
                'data'          => array(),
                'display_title' => true,
            ))
            ->setAllowedTypes(array(
                'builder'       => array('Symfony\Component\Form\FormBuilderInterface'),
                'display_title' => array('bool'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idci_step_form_form';
    }
}