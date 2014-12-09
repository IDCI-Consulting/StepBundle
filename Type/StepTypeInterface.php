<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Builder\StepBuilderInterface;
use IDCI\Bundle\StepBundle\Factory\StepFactoryInterface;
use IDCI\Bundle\StepBundle\View\StepView;
use IDCI\Bundle\StepBundle\StepInterface;

interface StepTypeInterface
{
    /**
     * Builds the step.
     *
     * @param StepBuilderInterface $builder The step builder
     * @param array                $options The options
     */
    public function buildStep(StepBuilderInterface $builder, array $options);

    /**
     * Builds the form view.
     *
     * @param StepView      $view    The view
     * @param StepInterface $step    The step
     * @param array         $options The options
     */
    public function buildView(StepView $view, StepInterface $step, array $options);

    /**
     * Returns a builder for the current type.
     * The builder is retrieved by going up in the type hierarchy when a type does not provide one.
     *
     * @param  StepFactoryInterface $factory    The step factory
     * @param  string               $name       The name of the builder
     * @param  array                $options    The options
     *
     * @return FormBuilder A step builder or null when the type does not have a builder
     */
    public function createBuilder(StepFactoryInterface $factory, $name, array $options);

    /**
     * Returns the configured options resolver used for this type.
     *
     * @return \Symfony\Component\OptionsResolver\OptionsResolverInterface The options resolver.
     */
    public function getOptionsResolver();

    /**
     * Sets the default options for this type.
     *
     * @param OptionsResolverInterface $resolver The options resolver.
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver);

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName();
}
