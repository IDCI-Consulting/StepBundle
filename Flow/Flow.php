<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

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
    public function setCurrentStep($step)
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
    public function getPreviousStep()
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
}