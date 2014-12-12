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
     * Sets a step type identify by a alias.
     *
     * @param string            $alias  The alias type.
     * @param StepTypeInterface $step   The type.
     *
     * @return StepRegistryInterface
     */
    public function setType($alias, StepTypeInterface $step);

    /**
     * Returns a step type by alias.
     *
     * @param string $alias The alias of the type.
     *
     * @return StepTypeInterface The type.
     *
     * @throws Exception\UnexpectedTypeException  if the passed alias is not a string.
     * @throws Exception\InvalidArgumentException if the type can not be retrieved.
     */
    public function getType($alias);

    /**
     * Returns whether the given step type is supported.
     *
     * @param string $alias The alias of the type.
     *
     * @return bool Whether the type is supported.
     */
    public function hasType($alias);
}
