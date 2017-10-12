<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Path\Type\PathTypeRegistryInterface;

class PathBuilder implements PathBuilderInterface
{
    /**
     * @var PathTypeRegistryInterface
     */
    private $registry;

    /**
     * Constructor.
     *
     * @param PathTypeRegistryInterface $registry
     */
    public function __construct(PathTypeRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function build($typeAlias, array $options = array(), array $steps = array())
    {
        $type = $this->registry->getType($typeAlias);

        $resolver = new OptionsResolver();
        $type->setDefaultOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return $type->buildPath(
            $steps,
            $resolvedOptions
        );
    }
}
