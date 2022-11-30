<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Step\StepInterface;

class FlowHistory implements FlowHistoryInterface
{
    /**
     * The taken paths.
     *
     * @var array
     */
    protected $takenPaths = [];

    /**
     * The full taken paths.
     *
     * @var array
     */
    protected $fullTakenPaths = [];

    /**
     * Constructor.
     */
    public function __construct(array $takenPaths = null, array $fullTakenPaths = null)
    {
        $this->takenPaths = $takenPaths;
        $this->fullTakenPaths = $fullTakenPaths;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrentStep(StepInterface $step): FlowHistoryInterface
    {
        if (empty($this->fullTakenPaths)) {
            $path = [
                'source' => null,
                'index' => null,
                'destination' => $step->getName(),
            ];
            $this->takenPaths[] = $path;
            $this->fullTakenPaths[] = $path;
        } else {
            $this->takenPaths[count($this->takenPaths) - 1]['destination'] = $step->getName();
            $this->fullTakenPaths[count($this->fullTakenPaths) - 1]['destination'] = $step->getName();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addTakenPath(StepInterface $step, int $pathId = 0)
    {
        $path = [
            'source' => $step->getName(),
            'index' => $pathId,
        ];
        $this->takenPaths[] = $path;
        $this->fullTakenPaths[] = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function retraceTakenPath(string $sourceStepName, StepInterface $destinationStep)
    {
        $this->fullTakenPaths[] = [
            'source' => $sourceStepName,
            'index' => '_back',
        ];

        $removedPaths = [];

        $reversedIndex = count($this->takenPaths) - 1;
        foreach (array_reverse($this->takenPaths) as $i => $path) {
            $removedPaths[] = $this->takenPaths[$reversedIndex - $i];
            unset($this->takenPaths[$reversedIndex - $i]);

            if ($destinationStep->getName() == $path['source']) {
                break;
            }
        }

        return $removedPaths;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastTakenPath(): ?array
    {
        $i = count($this->takenPaths) - 1;

        return isset($this->takenPaths[$i]) ? $this->takenPaths[$i] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTakenPaths(): array
    {
        return $this->takenPaths;
    }

    /**
     * {@inheritdoc}
     */
    public function getFullTakenPaths(): array
    {
        return $this->fullTakenPaths;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDoneStep(StepInterface $step, bool $full = false): bool
    {
        $takenPaths = (bool) $full ? $this->fullTakenPaths : $this->takenPaths;
        foreach ($takenPaths as $takenPath) {
            if (isset($takenPath['source']) && $takenPath['source'] == $step->getName()) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        return [
            'takenPaths' => $this->takenPaths,
            'fullTakenPaths' => $this->fullTakenPaths,
        ];
    }
}
