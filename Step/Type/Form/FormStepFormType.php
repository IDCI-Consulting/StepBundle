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
            $fieldOptions = $fieldBuilder->getOptions();
            if (isset($options['data'][$fieldName])) {
               // $fieldOptions['data'] = $options['data'][$fieldName];
            }

            $builder->add($fieldName, $fieldFormTypeClass, $fieldOptions);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(['builder'])
            ->setAllowedTypes('builder', ['Symfony\Component\Form\FormBuilderInterface'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'idci_step_step_form_form';
    }
}
