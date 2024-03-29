<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use IDCI\Bundle\StepBundle\Form\Type\NextButtonType;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\Path;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractPathType implements PathTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('type', NextButtonType::class)->setAllowedTypes('type', ['string'])
            ->setDefault('next_options', ['label' => 'Next >'])->setAllowedTypes('next_options', ['array'])
            ->setDefault('events', [])->setAllowedTypes('events', ['array'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(array $steps, array $options = []): PathInterface
    {
        return new Path($this, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function resolveDestination(array $options, NavigatorInterface $navigator): ?string
    {
        return $this->doResolveDestination($options, $navigator);
    }

    /**
     * Do resolve the destination step name.
     */
    abstract public function doResolveDestination(array $options, NavigatorInterface $navigator): ?string;
}
