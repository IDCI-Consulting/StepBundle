<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Step\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormBuilderInterface;

class FormStepType extends AbstractStepType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setRequired(array('builder'))
            ->setDefaults(array('data' => array()))
            ->setNormalizers(array(
                'data' => function (Options $options, $value) {
                    if (isset($value['_data'])) {
                        return $value['_data'];
                    }

                    return $value;
                }
            ))
            ->setAllowedTypes(array(
                'builder' => array('Symfony\Component\Form\FormBuilderInterface'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function doBuildNavigationStepForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('_data', 'form_content', array(
            'label'   => $options['title'],
            'builder' => $options['builder'],
            'data'    => $options['data'],
        ));
    }
}