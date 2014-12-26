<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Path\Type\PathTypeInterface;

class PathBuilder implements PathBuilderInterface
{
    /**
     * @var PathRegistryInterface
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
     * Create a Path.
     *
     * @param PathTypeInterface $type    The type.
     * @param array             $options The options.
     *
     * @return PathInterface The created Path.
     */
    protected static function createPath(PathTypeInterface $type, array $options = array())
    {
        // TODO: Use a PathConfig as argument instead of an array.
        return new Path(array(
            'type'    => $type,
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
            self::createPath($type, $resolvedOptions),
            $map,
            $resolvedOptions
        );
    }
}