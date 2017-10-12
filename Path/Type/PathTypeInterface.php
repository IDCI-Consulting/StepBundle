<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use IDCI\Bundle\StepBundle\Path\PathInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

interface PathTypeInterface
{
    /**
     * Sets the default options for this type.
     *
     * @param OptionsResolver $resolver the options resolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Build a path.
     *
     * @param array $steps   the map steps
     * @param array $options the options
     *
     * @return PathInterface the built path
     */
    public function buildPath(array $steps, array $options = array());

    /**
     * Resolve the destination step name.
     *
     * @param array              $options   the path options
     * @param NavigatorInterface $navigator the navigator
     *
     * @return string|null the resolved destination step name
     */
    public function resolveDestination(array $options, NavigatorInterface $navigator);
}
