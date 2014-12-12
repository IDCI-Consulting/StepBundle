<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step;

use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Map\MapInterface;

class StepBuilder implements StepBuilderInterface
{
    /**
     * @var StepRegistry
     */
    private $registry;

    /**
     * Constructor
     *
     * @param StepRegistry  $registry
     */
    public function __construct(StepRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public static function createStep($name, $typeAlias, array $options = array())
    {
        // TODO: Use a StepConfig
        return new Step(array(
            'name'      => $name,
            'type'      => $typeAlias,
            'options'   => $options
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function build($name, $typeAlias, array $options = array(), MapInterface & $map)
    {
        $type = $this->registry->getType($typeAlias);

        $resolver = new OptionsResolver();
        $type->setDefaultOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return $type->buildStep(
            self::createStep($name, $typeAlias, $resolvedOptions),
            $map,
            $resolvedOptions
        );
    }
}