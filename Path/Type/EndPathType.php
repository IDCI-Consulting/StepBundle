<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\Options;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class EndPathType extends AbstractPathType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(Options $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setRequired(array('source'))
            ->setDefaults(array('label' => 'end'))
            ->setAllowedTypes('source', array('string'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(array $steps, array $options = array())
    {
        $path = parent::buildPath($steps, $options);

        return $path->setSource($steps[$options['source']]);
    }

    /**
     * {@inheritdoc}
     */
    public function doResolveDestination(array $options, NavigatorInterface $navigator)
    {
        return null;
    }
}
