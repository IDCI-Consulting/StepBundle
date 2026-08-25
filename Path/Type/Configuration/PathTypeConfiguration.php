<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 *
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type\Configuration;

class PathTypeConfiguration implements PathTypeConfigurationInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var PathTypeConfiguration
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
    protected $extraFormOptions;

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

    public function getParent(): PathTypeConfigurationInterface
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
