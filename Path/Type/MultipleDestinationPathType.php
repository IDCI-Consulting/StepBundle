<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;

abstract class MultipleDestinationPathType extends AbstractPathType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('source', 'label', 'destinations'))
            ->setDefaults(array('label' => 'next'))
            ->setAllowedTypes(array(
                'source'        => 'string',
                'label'         => 'string',
                'destinations'  => 'array',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(PathInterface $path, MapInterface $map, array $options = array())
    {
        $path->setSource($map->getStep($options['source']));

        foreach ($options['destinations'] as $destName => $destOptions) {
            $path->addDestination($map->getStep($destName));
        }

        return $path;
    }
}