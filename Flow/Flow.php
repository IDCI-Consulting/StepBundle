<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;

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
     * Constructor
     *
     * @param StepInterface     $currentStep
     * @param mixed             $history
     * @param FlowDataInterface $data
     */
    public function __construct(
        StepInterface $currentStep = null,
        $history                   = null,
        FlowDataInterface $data    = null
    )
    {
        $this->history = $this->buildFlowHistory($history);
        $this->data    = null === $data ? new FlowData() : $data;

        if (null !== $currentStep) {
            $this->setCurrentStep($currentStep);
        }
    }

    /**
     * Build the FlowHistory object
     *
     * @param mixed $history
     *
     * @return FlowHistory
     */
    protected function buildFlowHistory($history)
    {
        if ($history instanceof FlowHistory) {
            return $history;
        }

        return new FlowHistory($history, $history);
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrentStep(StepInterface $step)
    {
        $this->currentStepName = $step->getName();
        $this->history->setCurrentStep($step);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentStepName()
    {
        return $this->currentStepName;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviousStepName()
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
    public function setHistory(FlowHistoryInterface $history)
    {
        $this->history = $history;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * {@inheritdoc}
     */
    public function getTakenPaths()
    {
        return $this->history->getTakenPaths();
    }

    /**
     * {@inheritdoc}
     */
    public function hasDoneStep(StepInterface $step, $full = false)
    {
        return $this->history->hasDoneStep($step);
    }

    /**
     * {@inheritdoc}
     */
    public function setData(FlowDataInterface $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function hasStepData(StepInterface $step, $type = null)
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
    public function getStepData(StepInterface $step, $type = null)
    {
        $stepName = $step->getName();

        if ($this->data->hasStepData($stepName, $type)) {
            return $this->data->getStepData($stepName, $type);
        }

        if (null === $type) {
            return $this->getStepData($step, FlowData::TYPE_REMINDED);
        }

        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function setStepData(StepInterface $step, array $data, $type = null)
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
    public function retraceTo(StepInterface $step)
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
    public function takePath(PathInterface $path, $index)
    {
        $this->history->addTakenPath($path->getSource(), $index);
    }
}

