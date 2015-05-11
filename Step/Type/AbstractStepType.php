<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use IDCI\Bundle\StepBundle\Step\Step;

abstract class AbstractStepType implements StepTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'title'            => null,
                'nav_title'        => null,
                'description'      => null,
                'nav_description'  => null,
                'is_first'         => false,
                'data'             => null,
                'prevent_previous' => false,
                'prevent_next'     => false,
                'previous_options' => array(
                    'label' => '< Previous'
                ),
                'js'               => null,
                'css'              => null,
                'events'           => array(),
            ))
            ->setAllowedTypes(array(
                'title'            => array('null', 'string'),
                'nav_title'        => array('null', 'string'),
                'description'      => array('null', 'string'),
                'nav_description'  => array('null', 'string'),
                'is_first'         => array('bool'),
                'data'             => array('null', 'array'),
                'previous_options' => array('array'),
                'js'               => array('null', 'string'),
                'css'              => array('null', 'string'),
                'events'           => array('array'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildStep($name, array $options = array())
    {
        // TODO: Use a StepConfig as argument instead of an array.
        return new Step(array(
            'name'      => $name,
            'type'      => $this,
            'options'   => $options
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildNavigationStepForm(FormBuilderInterface $builder, array $options)
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);

        $this->doBuildNavigationStepForm($builder, $resolvedOptions);
    }

    /**
     * Do build the navigation step form.
     *
     * @param FormBuilderInterface $builder The builder.
     * @param array                $options The options.
     */
    abstract public function doBuildNavigationStepForm(FormBuilderInterface $builder, array $options);

    /**
     * {@inheritdoc}
     */
    public function getDataTypeMapping($options)
    {
        return array();
    }
}
