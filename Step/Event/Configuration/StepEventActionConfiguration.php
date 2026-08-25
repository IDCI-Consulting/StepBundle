<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 *
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
     * @var bool
     */
    protected $abstract;

    /**
     * @var array
     */
    protected $extraFormOptionsResolver;

    /**
     * Constructor.
     */
    public function __construct(array $configuration)
    {
        $this->name = $configuration['name'];
        $this->parent = $configuration['parent'];
        $this->description = $configuration['description'];
        $this->abstract = $configuration['abstract'];
        $this->extraFormOptions = $configuration['extra_form_options'];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParent(): StepEventActionConfigurationInterface
    {
        return $this->parent;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isAbstract(): bool
    {
        return $this->abstract;
    }

    public function getExtraFormOptions(): array
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
