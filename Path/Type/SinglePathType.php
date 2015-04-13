<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class SinglePathType extends AbstractPathType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setRequired(array('source', 'destination'))
            ->setAllowedTypes(array(
                'source'      => 'string',
                'destination' => 'string',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(array $steps, array $options = array())
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
    public function resolveDestination(array $options, NavigatorInterface $navigator)
    {
        return $options['destination'];
    }
}