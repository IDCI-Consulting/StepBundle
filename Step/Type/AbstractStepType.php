<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use IDCI\Bundle\StepBundle\Step\Step;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

abstract class AbstractStepType implements StepTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'title'                 => null,
                'display_title'         => true,
                'nav_title'             => null,
                'description'           => null,
                'nav_description'       => null,
                'is_first'              => false,
                'pre_step_content'      => null,
                'data'                  => null,
                'prevent_previous'      => false,
                'prevent_next'          => false,
                'previous_options'      => array(
                    'label' => '< Previous'
                ),
                'js'                    => null,
                'css'                   => null,
                'attr'                  => array(),
                'events'                => array(),
                'serialization_mapping' => null,
            ))
            ->setAllowedTypes(array(
                'title'                 => array('null', 'string'),
                'display_title'         => array('bool'),
                'nav_title'             => array('null', 'string'),
                'description'           => array('null', 'string'),
                'nav_description'       => array('null', 'string'),
                'is_first'              => array('bool', 'string'),
                'data'                  => array('null', 'array'),
                'previous_options'      => array('array'),
                'js'                    => array('null', 'string'),
                'css'                   => array('null', 'string'),
                'attr'                  => array('array'),
                'events'                => array('array'),
                'serialization_mapping' => array('null', 'array'),
            ))
            ->setNormalizers(array(
                'is_first' => function (Options $options, $value) {
                    return (bool)$value;
                }
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
            'name'    => $name,
            'type'    => $this,
            'options' => $options
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function prepareNavigation(NavigatorInterface $navigator, array $options)
    {
        return $options;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function buildNavigationStepForm(FormBuilderInterface $builder, array $options);

    /**
     * {@inheritdoc}
     */
    public function getDataTypeMapping($options)
    {
        if (null === $options['serialization_mapping']) {
            return array();
        }

        return $options['serialization_mapping'];
    }
}
