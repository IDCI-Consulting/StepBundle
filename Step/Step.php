<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
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

    /**
     * {@inheritdoc}
     */
    public function setOptions($options): StepInterface
    {
        $this->options = $options;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function isFirst(): bool
    {
        return $this->options['is_first'];
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): StepTypeInterface
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): ?array
    {
        return isset($this->options['data']) ? $this->options['data'] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreStepContent(): ?string
    {
        return isset($this->options['pre_step_content']) ? $this->options['pre_step_content'] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataTypeMapping(): array
    {
        return $this->getType()->getDataTypeMapping($this->options);
    }
}
