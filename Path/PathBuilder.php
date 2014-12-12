<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;

class PathBuilder implements PathBuilderInterface
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
    public function __construct(PathRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public static function createPath($typeAlias, array $options = array())
    {
        // TODO: Use a PathConfig
        return new Path(array(
            'type'    => $typeAlias,
            'options' => $options
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function build($typeAlias, array $options = array(), MapInterface & $map)
    {
        $type = $this->registry->getType($typeAlias);

        $resolver = new OptionsResolver();
        $type->setDefaultOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return $type->buildPath(
            self::createPath($typeAlias, $resolvedOptions),
            $map,
            $resolvedOptions
        );
    }
}