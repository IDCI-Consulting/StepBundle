<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 *
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step;

use IDCI\Bundle\StepBundle\Step\Type\StepTypeInterface;

class Step implements StepInterface
{
    /**
     * @var StepTypeInterface
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     */
    public function __construct(string $name, StepTypeInterface $type, array $options = [])
    {
        $this->name = $name;
        $this->type = $type;
        $this->options = $options;
    }

    public function setOptions($options): StepInterface
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isFirst(): bool
    {
        return $this->options['is_first'];
    }

    public function getType(): StepTypeInterface
    {
        return $this->type;
    }

    public function getData(): ?array
    {
        return isset($this->options['data']) ? $this->options['data'] : null;
    }

    public function getPreStepContent(): ?string
    {
        return isset($this->options['pre_step_content']) ? $this->options['pre_step_content'] : null;
    }

    public function getDataTypeMapping(): array
    {
        return $this->getType()->getDataTypeMapping($this->options);
    }
}
