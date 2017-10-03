<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step;

use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Step\Type\StepTypeRegistryInterface;

class StepBuilder implements StepBuilderInterface
{
    /**
     * @var StepTypeRegistryInterface
     */
    private $registry;

    /**
     * Constructor.
     *
     * @param StepTypeRegistryInterface $registry
     */
    public function __construct(StepTypeRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function build($name, $typeAlias, array $options = array())
    {
        $type = $this->registry->getType($typeAlias);

        $resolver = new OptionsResolver();
        $type->setDefaultOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return $type->buildStep(
            $name,
            $resolvedOptions
        );
    }
}
