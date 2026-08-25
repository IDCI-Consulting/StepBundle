<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 *
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

    public function setOptions(array $options): PathInterface
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setSource(StepInterface $step): PathInterface
    {
        $this->source = $step;

        return $this;
    }

    public function getSource(): StepInterface
    {
        return $this->source;
    }

    public function addDestination(StepInterface $step): PathInterface
    {
        $this->destinations[$step->getName()] = $step;

        return $this;
    }

    public function getDestinations(): array
    {
        return $this->destinations;
    }

    public function hasDestination(string $name): bool
    {
        return isset($this->destinations[$name]);
    }

    public function getDestination(string $name): ?StepInterface
    {
        return $this->hasDestination($name) ?
            $this->destinations[$name] :
            null
        ;
    }

    public function resolveDestination(NavigatorInterface $navigator): ?StepInterface
    {
        $destinationName = $this
            ->getType()
            ->resolveDestination($this->getOptions(), $navigator)
        ;

        return null === $destinationName ? null : $this->getDestination($destinationName);
    }

    public function getType(): PathTypeInterface
    {
        return $this->type;
    }
}
