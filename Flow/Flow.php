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
    private $currentStep;

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
        $currentStep  = null,
        $history      = null,
        $data         = null
    )
    {
        $this->currentStep  = $currentStep;
        $this->history      = null === $history ? new FlowHistory() : $history;
        $this->data         = null === $data ? new FlowData() : $data;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrentStep(StepInterface $step)
    {
        $this->currentStep = $step;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentStep()
    {
        return $this->currentStep;
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
    public function getStepData(StepInterface $step)
    {
        $stepName = $step->getName();

        if ($this->data->hasStepData($stepName)) {
            $this->data->getStepData($stepName);
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