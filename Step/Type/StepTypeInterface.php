<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

interface StepTypeInterface
{
    /**
     * Sets the default options for this type.
     *
     * @param OptionsResolver $resolver the options resolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Build a step.
     *
     * @param string $name    the step name
     * @param array  $options the options
     *
     * @return StepInterface the built step
     */
    public function buildStep($name, array $options = array());

    /**
     * Prepare the navigation before building the navigation step form.
     * Allow to change the step configuration options.
     *
     * @param NavigatorInterface $navigator the navigator
     * @param array              $options   the configuration options
     *
     * @return array the transformed configuration options
     */
    public function prepareNavigation(NavigatorInterface $navigator, array $options);

    /**
     * Build the navigation step form.
     *
     * @param FormBuilderInterface $builder the builder
     * @param array                $options the options
     */
    public function buildNavigationStepForm(FormBuilderInterface $builder, array $options);

    /**
     * Returns the step data type mapping.
     *
     * @param array $options the options
     *
     * @return array the data type mapping
     */
    public function getDataTypeMapping($options);
}
