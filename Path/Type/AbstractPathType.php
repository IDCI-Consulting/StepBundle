<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Path\Path;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

abstract class AbstractPathType implements PathTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'type' => SubmitType::class,
                'next_options' => array(
                    'label' => 'Next >',
                ),
                'events' => array(),
            ))
            ->setAllowedTypes('type', array('string'))
            ->setAllowedTypes('next_options', array('array'))
            ->setAllowedTypes('events', array('array'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(array $steps, array $options = array())
    {
        // TODO: Use a PathConfig as argument instead of an array.
        return new Path(array(
            'type' => $this,
            'options' => $options,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function resolveDestination(array $options, NavigatorInterface $navigator)
    {
        return $this->doResolveDestination($options, $navigator);
    }

    /**
     * Do resolve the destination step name.
     *
     * @param array              $options   the path options
     * @param NavigatorInterface $navigator the navigator
     *
     * @return string|null the resolved destination step name
     */
    abstract public function doResolveDestination(array $options, NavigatorInterface $navigator);
}
