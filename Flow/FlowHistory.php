<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

class FlowHistory implements FlowHistoryInterface
{
    /**
     * The current step identifier name.
     *
     * @var string
     */
    protected $currentStep = '';

    /**
     * The done steps.
     *
     * @var array
     */
    protected $doneSteps = array();

    /**
     * {@inheritdoc}
     */
    public function addDoneStep($name)
    {
        $this->doneSteps[] = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDoneStep($name)
    {
        foreach ($this->doneSteps as $step) {
            if ($step === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function retraceDoneStep($name)
    {
        $remove = false;
        $removedSteps = array();

        foreach ($this->doneSteps as $i => $step) {
            if (!$remove && $step === $name) {
                $remove = true;
                $this->currentStep = $step;
            }

            if ($remove) {
                $removedSteps[] = $step;
                unset($this->doneSteps[$i]);
            }
        }

        $this->doneSteps = array_values($this->doneSteps);

        return $removedSteps;
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
    public function setCurrentStep($name)
    {
        $this->currentStep = $name;
    }
}
