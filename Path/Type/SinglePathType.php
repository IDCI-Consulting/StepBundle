<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SinglePathType extends AbstractPathType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(['source', 'destination'])
            ->setAllowedTypes('source', 'string')
            ->setAllowedTypes('destination', 'string')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(array $steps, array $options = []): PathInterface
    {
        $path = parent::buildPath($steps, $options);

        return $path
            ->setSource($steps[$options['source']])
            ->addDestination($steps[$options['destination']])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function doResolveDestination(array $options, NavigatorInterface $navigator): ?string
    {
        return $options['destination'];
    }
}
