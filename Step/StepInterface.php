<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle;

use IDCI\Bundle\StepBundle\StepView;

interface StepInterface extends \ArrayAccess, \Traversable, \Countable
{
    /**
     * Sets the parent step.
     *
     * @param StepInterface|null $parent The parent step or null if it's the root.
     *
     * @return StepInterface The step instance
     *
     * @throws Exception\LogicException  When trying to set a parent for a step with an empty name.
     */
    public function setParent(StepInterface $parent = null);

    /**
     * Returns the parent step.
     *
     * @return StepInterface|null The parent step or null if there is none.
     */
    public function getParent();

    /**
     * Adds or replaces a child to the step.
     *
     * @param StepInterface|string|int $child   The StepInterface instance or the name of the child.
     * @param string|null              $type    The child's type, if a name was passed.
     * @param array                    $options The child's options, if a name was passed.
     *
     * @return StepInterface The step instance
     *
     * @throws Exception\UnexpectedTypeException   If $child or $type has an unexpected type.
     */
    public function add($child, $type = null, array $options = array());

    /**
     * Returns the child with the given name.
     *
     * @param string $name The name of the child
     *
     * @return StepInterface The child step
     *
     * @throws \OutOfBoundsException If the named child does not exist.
     */
    public function get($name);

    /**
     * Returns whether a child with the given name exists.
     *
     * @param string $name The name of the child
     *
     * @return bool
     */
    public function has($name);

    /**
     * Removes a child from the step.
     *
     * @param string $name The name of the child to remove
     *
     * @return StepInterface The form instance
     */
    public function remove($name);

    /**
     * Returns all children in this group.
     *
     * @return StepInterface[] An array of StepInterface instances
     */
    public function all();

    /**
     * Updates the step with default data.
     *
     * @param mixed $data The data
     *
     * @return StepInterface The form instance
     */
    public function setData($data);

    /**
     * Returns the data.
     *
     * @return mixed
     */
    public function getData();

    /**
     * Returns the name by which the step is identified in steps.
     *
     * @return string The name of the step.
     */
    public function getName();

    /**
     * Inspects the given request and calls {@link submit()} if the step was
     * submitted.
     *
     * Internally, the request is forwarded to the configured
     * {@link RequestHandlerInterface} instance, which determines whether to
     * submit the step or not.
     *
     * @param mixed $request The request to handle.
     *
     * @return StepInterface The form instance.
     */
    public function handleRequest($request = null);

    /**
     * Submits data to the step.
     *
     * @param mixed $submittedData The submitted data.
     *
     * @return StepInterface The step instance
     *
     * @throws Exception\AlreadySubmittedException If the step has already been submitted.
     */
    public function submit($submittedData);

    /**
     * Returns the root of the step tree.
     *
     * @return StepInterface The root of the tree
     */
    public function getRoot();

    /**
     * Returns whether the step is the root of the step tree.
     *
     * @return bool
     */
    public function isRoot();

    /**
     * Creates a view.
     *
     * @param StepView $parent The parent view
     *
     * @return StepView The view
     */
    public function createView(StepView $parent = null);
}
