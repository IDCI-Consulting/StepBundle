<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\Type\PathTypeInterface;
use IDCI\Bundle\StepBundle\Step\StepInterface;

class Path implements PathInterface
{
    /**
     * The type.
     *
     * @var PathTypeInterface
     */
    protected $type;

    /**
     * The options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * The source step.
     *
     * @var StepInterface
     */
    protected $source;

    /**
     * The destinations step.
     *
     * @var array
     */
    protected $destinations = [];

    /**
     * Constructor.
     */
    public function __construct(PathTypeInterface $type, array $options = [])
    {
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options): PathInterface
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
    public function setSource(StepInterface $step): PathInterface
    {
        $this->source = $step;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource(): StepInterface
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function addDestination(StepInterface $step): PathInterface
    {
        $this->destinations[$step->getName()] = $step;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDestinations(): array
    {
        return $this->destinations;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDestination(string $name): bool
    {
        return isset($this->destinations[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDestination(string $name): ?StepInterface
    {
        return $this->hasDestination($name) ?
            $this->destinations[$name] :
            null
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveDestination(NavigatorInterface $navigator): ?StepInterface
    {
        $destinationName = $this
            ->getType()
            ->resolveDestination($this->getOptions(), $navigator)
        ;

        return null === $destinationName ? null : $this->getDestination($destinationName);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): PathTypeInterface
    {
        return $this->type;
    }
}
