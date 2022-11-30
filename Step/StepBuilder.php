<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step;

use IDCI\Bundle\StepBundle\Step\Type\StepTypeRegistryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StepBuilder implements StepBuilderInterface
{
    /**
     * @var StepTypeRegistryInterface
     */
    private $registry;

    /**
     * Constructor.
     */
    public function __construct(StepTypeRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function build(string $name, string $typeAlias, array $options = []): StepInterface
    {
        $type = $this->registry->getType($typeAlias);

        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return $type->buildStep($name, $resolvedOptions);
    }
}
