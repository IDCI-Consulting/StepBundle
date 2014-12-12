<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step;

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
    public function build($type, array $options = array())
    {
        $type = $this->registry->getType($type);
        // Resolved options

        return new Step($type, $options);
    }
}