<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface PathTypeInterface
{
    /**
     * Sets the default options for this type.
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Build a path.
     */
    public function buildPath(array $steps, array $options = []): PathInterface;

    /**
     * Resolve the destination step name.
     */
    public function resolveDestination(array $options, NavigatorInterface $navigator): ?string;
}
