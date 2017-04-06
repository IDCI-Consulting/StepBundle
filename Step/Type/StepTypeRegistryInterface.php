<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Step\Type;

use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

interface StepTypeRegistryInterface
{
    /**
     * Sets a step type identify by a alias.
     *
     * @param string            $alias  The alias type.
     * @param StepTypeInterface $step   The type.
     *
     * @return StepTypeRegistryInterface
     */
    public function setType($alias, StepTypeInterface $step);

    /**
     * Returns a step type by alias.
     *
     * @param string $alias The alias of the type.
     *
     * @return StepTypeInterface The type.
     *
     * @throws UnexpectedTypeException if the passed alias is not a string.
     * @throws \InvalidArgumentException if the type can not be retrieved.
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
