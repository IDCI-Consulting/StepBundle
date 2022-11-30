<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

use IDCI\Bundle\StepBundle\Path\Type\PathTypeRegistryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PathBuilder implements PathBuilderInterface
{
    /**
     * @var PathTypeRegistryInterface
     */
    private $registry;

    /**
     * Constructor.
     */
    public function __construct(PathTypeRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function build(string $typeAlias, array $options = [], array $steps = []): PathInterface
    {
        $type = $this->registry->getType($typeAlias);

        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return $type->buildPath($steps, $resolvedOptions);
    }
}
