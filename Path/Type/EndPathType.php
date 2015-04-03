<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;

class EndPathType extends AbstractPathType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setRequired(array('source'))
            ->setDefaults(array('label' => 'end'))
            ->setAllowedTypes(array(
                'source' => 'string'
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(MapInterface $map, array $options = array())
    {
        $path = parent::buildPath($map, $options);

        return $path->setSource($map->getStep($options['source']));
    }
}