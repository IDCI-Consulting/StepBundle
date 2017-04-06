<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Configuration;

class StepEventActionConfiguration implements StepEventActionConfigurationInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var StepEventActionConfiguration
     */
    protected $parent;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var boolean
     */
    protected $abstract;

    /**
     * @var array
     */
    protected $extraFormOptions;

    /**
     * Constructor
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->name                 = $configuration['name'];
        $this->parent               = $configuration['parent'];
        $this->description          = $configuration['description'];
        $this->abstract             = $configuration['abstract'];
        $this->extraFormOptions     = $configuration['extra_form_options'];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     */
    public function isAbstract()
    {
        return $this->abstract;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtraFormOptions()
    {
        if (null === $this->getParent()) {
            return $this->extraFormOptions;
        }

        return array_merge_recursive(
            $this->getParent()->getExtraFormOptions(),
            $this->extraFormOptions
        );
    }
}
