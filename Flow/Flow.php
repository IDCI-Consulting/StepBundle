<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Step\StepInterface;

class Flow implements FlowInterface
{
    /**
     * @var string
     */
    private $currentStepName;

    /**
     * @var FlowHistoryInterface
     */
    private $history;

    /**
     * @var FlowDataInterface
     */
    private $data;

    /**
     * Constructor.
     *
     * @param StepInterface     $currentStep
     * @param mixed             $history
     * @param FlowDataInterface $data
     */
    public function __construct(StepInterface $currentStep = null, $history = null, FlowDataInterface $data = null)
    {
        $this->history = $this->buildFlowHistory($history);
        $this->data = null === $data ? new FlowData() : $data;

        if (null !== $currentStep) {
            $this->setCurrentStep($currentStep);
        }
    }

    /**
     * Build the FlowHistory object.
     *
     * @param mixed $history
     */
    protected function buildFlowHistory($history): FlowHistory
    {
        if ($history instanceof FlowHistory) {
            return $history;
        }

        return new FlowHistory($history, $history);
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrentStep(StepInterface $step): FlowInterface
    {
        $this->currentStepName = $step->getName();
        $this->history->setCurrentStep($step);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentStepName(): ?string
    {
        return $this->currentStepName;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviousStepName(): ?string
    {
        $lastTakenPath = $this->getHistory()->getLastTakenPath();

        if (isset($lastTakenPath['source'])) {
            return $lastTakenPath['source'];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setHistory(FlowHistoryInterface $history): FlowInterface
    {
        $this->history = $history;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHistory(): FlowHistoryInterface
    {
        return $this->history;
    }

    /**
     * {@inheritdoc}
     */
    public function getTakenPaths(): array
    {
        return $this->history->getTakenPaths();
    }

    /**
     * {@inheritdoc}
     */
    public function hasDoneStep(StepInterface $step, $full = false): bool
    {
        return $this->history->hasDoneStep($step);
    }

    /**
     * {@inheritdoc}
     */
    public function setData(FlowDataInterface $data): FlowInterface
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): FlowDataInterface
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function hasStepData(StepInterface $step, $type = null): bool
    {
        $stepName = $step->getName();

        if ($this->data->hasStepData($stepName, $type)) {
            return true;
        }

        if (null === $type) {
            return $this->hasStepData($step, FlowData::TYPE_REMINDED);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStepData(StepInterface $step, $type = null): array
    {
        $stepName = $step->getName();

        if ($this->data->hasStepData($stepName, $type)) {
            return $this->data->getStepData($stepName, $type);
        }

        if (null === $type) {
            return $this->getStepData($step, FlowData::TYPE_REMINDED);
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function setStepData(StepInterface $step, array $data, $type = null): FlowInterface
    {
        $this->data->setStepData(
            $step->getName(),
            $data,
            $type
        );

        if (null === $type) {
            $this->data->setStepData(
                $step->getName(),
                $data,
                FlowData::TYPE_REMINDED
            );
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function retraceTo(StepInterface $step): array
    {
        $retracedPaths = $this->history->retraceTakenPath(
            $this->getCurrentStepName(),
            $step
        );

        foreach ($retracedPaths as $retracedPath) {
            $this->data->unsetStepData($retracedPath['source']);
        }

        $this->setCurrentStep($step);
    }

    /**
     * {@inheritdoc}
     */
    public function takePath(PathInterface $path, int $index)
    {
        $this->history->addTakenPath($path->getSource(), $index);
    }
}
