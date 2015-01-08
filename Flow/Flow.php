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
     * @var FlowDataInterface
     */
    private $remindedData;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->currentStep  = null;
        $this->history      = new FlowHistory();
        $this->data         = new FlowData();
        $this->remindedData = new FlowData();
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
    public function setCurrentStep($step)
    {
        $this->currentStep = $step;

        return $this;
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
    public function getHistory()
    {
        return $this->history;
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
    public function getData()
    {
        return $this->data;
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
    public function getRemindedData()
    {
        return $this->remindedData;
    }

    /**
     * {@inheritdoc}
     */
    public function setRemindedData(FlowDataInterface $data)
    {
        $this->remindedData = $data;

        return $this;
    }
}