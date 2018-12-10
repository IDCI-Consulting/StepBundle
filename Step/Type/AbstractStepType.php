<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormBuilderInterface;
use IDCI\Bundle\StepBundle\Step\Step;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

abstract class AbstractStepType implements StepTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'title' => null,
                'display_title' => true,
                'nav_title' => null,
                'description' => null,
                'nav_description' => null,
                'is_first' => false,
                'pre_step_content' => null,
                'data' => null,
                'prevent_previous' => false,
                'prevent_next' => false,
                'previous_options' => array(
                    'label' => '< Previous',
                ),
                'js' => null,
                'css' => null,
                'attr' => array(),
                'events' => array(),
                'serialization_mapping' => null,
            ))
            ->setAllowedTypes('title', array('null', 'string'))
            ->setAllowedTypes('display_title', array('bool'))
            ->setAllowedTypes('nav_title', array('null', 'string'))
            ->setAllowedTypes('description', array('null', 'string'))
            ->setAllowedTypes('nav_description', array('null', 'string'))
            ->setAllowedTypes('is_first', array('bool', 'string'))
            ->setAllowedTypes('data', array('null', 'array'))
            ->setAllowedTypes('prevent_previous', array('bool', 'string'))
            ->setAllowedTypes('prevent_next', array('bool', 'string'))
            ->setAllowedTypes('previous_options', array('array'))
            ->setAllowedTypes('js', array('null', 'string'))
            ->setAllowedTypes('css', array('null', 'string'))
            ->setAllowedTypes('attr', array('array'))
            ->setAllowedTypes('events', array('array'))
            ->setAllowedTypes('serialization_mapping', array('null', 'array'))
            ->setNormalizer('is_first', function (Options $options, $value) {
                return (bool) $value;
            })
            ->setNormalizer('prevent_previous', function (Options $options, $value) {
                return (bool) $value;
            })
            ->setNormalizer('prevent_next', function (Options $options, $value) {
                return (bool) $value;
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildStep($name, array $options = array())
    {
        // TODO: Use a StepConfig as argument instead of an array.
        return new Step(array(
            'name' => $name,
            'type' => $this,
            'options' => $options,
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
