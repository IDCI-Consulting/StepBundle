<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

interface PathTypeInterface
{
    /**
     * Sets the default options for this type.
     *
     * @param OptionsResolverInterface $resolver The options resolver.
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver);

    /**
     * Build a path.
     *
     * @param array $steps   The map steps.
     * @param array $options The options.
     *
     * @return PathInterface The built path.
     */
    public function buildPath(array $steps, array $options = array());

    /**
     * Resolve the destination step name.
     *
     * @param array              $options   The path options.
     * @param NavigatorInterface $navigator The navigator.
     *
     * @return string|null The resolved destination step name.
     */
    public function resolveDestination(array $options, NavigatorInterface $navigator);
}
