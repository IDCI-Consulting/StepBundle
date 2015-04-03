<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;

interface StepTypeInterface
{
    /**
     * Sets the default options for this type.
     *
     * @param OptionsResolverInterface $resolver The options resolver.
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver);

    /**
     * Build a step.
     *
     * @param string        $name       The step name.
     * @param MapInterface  $map        The map container.
     * @param array         $options    The options.
     *
     * @return StepInterface The built step.
     */
    public function buildStep($name, MapInterface $map, array $options = array());

    /**
     * Build the navigation step form.
     *
     * @param FormBuilderInterface $builder The builder.
     * @param array                $options The options.
     */
    public function buildNavigationStepForm(FormBuilderInterface $builder, array $options);
}
