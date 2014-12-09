<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Factory;

use IDCI\Bundle\StepBundle\Type\StepTypeInterface;

interface StepFactoryInterface
{
    /**
     * Returns a step.
     *
     * @see createBuilder()
     *
     * @param string|StepTypeInterface $type    The type of the step
     * @param mixed                    $data    The initial data
     * @param array                    $options The options
     *
     * @return StepInterface The form named after the type
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException if any given option is not applicable to the given type
     */
    public function create($type = 'flow', $data = null, array $options = array());

    /**
     * Returns a step.
     *
     * @see createNamedBuilder()
     *
     * @param string|int               $name    The name of the step
     * @param string|StepTypeInterface $type    The type of the step
     * @param mixed                    $data    The initial data
     * @param array                    $options The options
     *
     * @return StepInterface The form
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException if any given option is not applicable to the given type
     */
    public function createNamed($name, $type = 'flow', $data = null, array $options = array());

    /**
     * Returns a step builder.
     *
     * @param string|StepTypeInterface $type    The type of the step
     * @param mixed                    $data    The initial data
     * @param array                    $options The options
     *
     * @return StepBuilderInterface The step builder
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException if any given option is not applicable to the given type
     */
    public function createBuilder($type = 'flow', $data = null, array $options = array());

    /**
     * Returns a step builder.
     *
     * @param string|int               $name    The name of the step
     * @param string|StepTypeInterface $type    The type of the step
     * @param mixed                    $data    The initial data
     * @param array                    $options The options
     *
     * @return FormBuilderInterface The step builder
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException if any given option is not applicable to the given type
     */
    public function createNamedBuilder($name, $type = 'flow', $data = null, array $options = array());
}
