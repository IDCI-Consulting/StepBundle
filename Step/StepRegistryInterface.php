<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Step;

use IDCI\Bundle\StepBundle\Step\Type\StepTypeInterface;

interface StepRegistryInterface
{
    /**
     * Sets a step type identify by a name
     *
     * @param string            $name   The step name
     * @param StepTypeInterface $step   The step
     *
     * @return StepRegistryInterface
     */
    public function setType($name, StepTypeInterface $step);

    /**
     * Returns a step type by name.
     *
     * @param string $name The name of the type
     *
     * @return StepTypeInterface The type
     *
     * @throws Exception\UnexpectedTypeException  if the passed name is not a string
     * @throws Exception\InvalidArgumentException if the type can not be retrieved
     */
    public function getType($name);

    /**
     * Returns whether the given step type is supported.
     *
     * @param string $name The name of the type
     *
     * @return bool Whether the type is supported
     */
    public function hasType($name);
}
