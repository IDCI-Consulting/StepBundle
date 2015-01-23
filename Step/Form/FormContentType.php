<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Step\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormContentType extends AbstractType
{
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
            ->setDefaults(array('data' => array()))
            ->setAllowedTypes(array(
                'builder' => array('Symfony\Component\Form\FormBuilderInterface'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'form_content';
    }
}