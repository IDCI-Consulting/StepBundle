<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

use Symfony\Component\OptionsResolver\OptionsResolver;

class PathBuilder implements PathBuilderInterface
{
    /**
     * @var PathRegistryInterface
     */
    private $registry;

    /**
     * Constructor
     *
     * @param PathRegistryInterface $registry
     */
    public function __construct(PathRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function build($typeAlias, array $options = array(), array $steps)
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
