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
    public function __construct()
    {
        // TODO
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
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }
}