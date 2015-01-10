<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

abstract class AbstractPathType implements PathTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'next_options' => array(
                    'label' => 'Next >'
                ),
                'listeners'    => array(),
            ))
            ->setAllowedTypes(array(
                'next_options' => 'array',
                'listeners'    => 'array',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(PathInterface $path, MapInterface $map, array $options = array())
    {
        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveDestination(array $options, NavigatorInterface $navigator)
    {
        return null;
    }
}
