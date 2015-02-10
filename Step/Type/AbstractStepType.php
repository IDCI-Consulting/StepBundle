<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;

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
                'description'      => null,
                'is_first'         => false,
                'previous_options' => array(
                    'label' => '< Previous'
                ),
                'js'               => null,
                'css'              => null,
                //'listeners'        => array(),
            ))
            ->setAllowedTypes(array(
                'title'            => array('null', 'string'),
                'description'      => array('null', 'string'),
                'is_first'         => array('bool'),
                'previous_options' => array('array'),
                'js'               => array('null', 'string'),
                'css'              => array('null', 'string'),
                //'listeners'        => array('array'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildStep(StepInterface $step, MapInterface $map, array $options = array())
    {
        return $step;
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
}
