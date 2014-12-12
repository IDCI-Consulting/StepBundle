<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;

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
     * @param PathInterface $path       The building path.
     * @param MapInterface  $map        The map container.
     * @param array         $options    The options.
     *
     * @return PathInterface The built path.
     */
    public function buildPath(PathInterface $path, MapInterface $map, array $options = array());
}
