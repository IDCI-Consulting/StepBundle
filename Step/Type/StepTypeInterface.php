<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Step\StepInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface StepTypeInterface
{
    /**
     * Sets the default options for this type.
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Build a step.
     */
    public function buildStep(string $name, array $options = []): StepInterface;

    /**
     * Prepare the navigation before building the navigation step form.
     * Allow to change the step configuration options.
     */
    public function prepareNavigation(NavigatorInterface $navigator, array $options): array;

    /**
     * Build the navigation step form.
     */
    public function buildNavigationStepForm(FormBuilderInterface $builder, array $options);

    /**
     * Returns the step data type mapping.
     */
    public function getDataTypeMapping(array $options): array;
}
