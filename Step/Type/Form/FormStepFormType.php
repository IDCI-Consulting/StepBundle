<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormStepFormType extends AbstractStepFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['builder']->all() as $fieldName => $fieldBuilder) {
            $fieldFormTypeClass = get_class($fieldBuilder->getType()->getInnerType());
            $builder->add(
                $fieldName,
                $fieldFormTypeClass,
                $fieldBuilder->getOptions()
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(array('builder'))
            ->setAllowedTypes('builder', array('Symfony\Component\Form\FormBuilderInterface'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'idci_step_step_form_form';
    }
}
