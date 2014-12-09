<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Builder;

interface StepBuilderInterface extends \Traversable, \Countable
{
    /**
     * Adds a new step to this flow. A step must have a unique name within
     * the flow. Otherwise the existing step is overwritten.
     *
     * If you add a nested flow, this flow should also be represented in the
     * object hierarchy.
     *
     * @param string|StepBuilderInterface $child
     * @param string|StepTypeInterface    $type
     * @param array                       $options
     *
     * @return StepBuilderInterface The builder object.
     */
    public function add($child, $type = null, array $options = array());

    /**
     * Creates a step builder.
     *
     * @param string                   $name    The name of the flow or the name of the step
     * @param string|StepTypeInterface $type    The type of the step
     * @param array                    $options The options
     *
     * @return FormBuilderInterface The created builder.
     */
    public function create($name, $type = null, array $options = array());

    /**
     * Returns a child by name.
     *
     * @param string $name The name of the child
     *
     * @return StepBuilderInterface The builder for the child
     *
     * @throws Exception\InvalidArgumentException if the given child does not exist
     */
    public function get($name);

    /**
     * Removes the step with the given name.
     *
     * @param string $name
     *
     * @return StepBuilderInterface The builder object.
     */
    public function remove($name);

    /**
     * Returns whether a step with the given name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name);

    /**
     * Returns the children.
     *
     * @return array
     */
    public function all();

    /**
     * Creates the step.
     *
     * @return Step The step
     */
    public function getStep();
}
