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
     */
    public function __construct(
        StepInterface        $currentStep  = null,
        FlowHistoryInterface $history      = null,
        FlowDataInterface    $data         = null
    )
    {
        if (null !== $currentStep) {
            $this->setCurrentStep($currentStep);
        }

        $this->history = null === $history ? new FlowHistory() : $history;
        $this->data    = null === $data ? new FlowData() : $data;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrentStep(StepInterface $step)
    {
        $this->currentStepName = $step->getName();

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

        if (null !== $lastTakenPath) {
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
    public function setStepData(StepInterface $step, array $data)
    {
        $stepName = $step->getName();

        $this->data->setStepData($stepName, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getStepData(StepInterface $step, $reminded = true)
    {
        $stepName = $step->getName();

        if ($reminded) {
            if ($this->data->hasRemindedStepData($stepName)) {
                return $this->data->getRemindedStepData($stepName);
            }
        } elseif ($this->data->hasStepData($stepName)) {
            return $this->data->getStepData($stepName);
        }

        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function retraceTo(StepInterface $step)
    {
        $retracedPaths = $this->history->retraceTakenPath($step);

        foreach ($retracedPaths as $retracedPath) {
            $this->data->unsetStepData($retracedPath['source']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function takePath(PathInterface $path, $index)
    {
        $this->history->addTakenPath($path->getSource(), $index);
    }
}