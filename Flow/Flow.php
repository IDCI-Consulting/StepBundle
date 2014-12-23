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
    private $currentStep = 'intro';

    /**
     * @var FlowHistoryInterface
     */
    private $history;

    /**
     * @var FlowDataInterface
     */
    private $data;

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
    public function setCurrentStep()
    {
        $this->currentStep = $step;

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
}