<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;

class SinglePathType extends AbstractPathType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('source', 'label', 'destination', 'listeners'))
            ->setDefaults(array(
                'label'     => 'next',
                'listeners' => array()
            ))
            ->setAllowedTypes(array(
                'source'        => 'string',
                'label'         => 'string',
                'destination'   => 'string',
                'listeners'     => 'array',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(PathInterface $path, MapInterface $map, array $options = array())
    {
        return $path
            ->setSource($map->getStep($options['source']))
            ->addDestination($map->getStep($options['destination']))
        ;
    }
}