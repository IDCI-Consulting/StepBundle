<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

abstract class MultipleDestinationPathType extends AbstractPathType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setRequired(array('source', 'destinations'))
            ->setAllowedTypes(array(
                'source'        => 'string',
                'destinations'  => 'array',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(array $steps, array $options = array())
    {
        $path = parent::buildPath($steps, $options);
        $path->setSource($steps[$options['source']]);

        foreach ($options['destinations'] as $destName => $destOptions) {
            $path->addDestination($steps[$destName]);
        }

        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveDestination(array $options, NavigatorInterface $navigator)
    {
        $names = array_keys($options['destinations']);

        return $names[0];
    }
}