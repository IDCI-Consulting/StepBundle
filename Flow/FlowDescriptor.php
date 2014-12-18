<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

class FlowDescriptor implements FlowDescriptorInterface
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
            }

            if ($remove) {
                $this->currentStep = $step;
                $removedSteps[] = $step;
                unset($this->doneSteps[$i]);
            }
        }

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
