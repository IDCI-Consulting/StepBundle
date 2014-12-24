<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

class FlowHistory implements FlowHistoryInterface
{
    /**
     * The done steps.
     *
     * @var array
     */
    protected $doneSteps = array();

    /**
     * The full done steps.
     *
     * @var array
     */
    protected $fullDoneSteps = array();

    /**
     * The taken paths.
     *
     * @var array
     */
    protected $takenPaths = array();

    /**
     * {@inheritdoc}
     */
    public function addDoneStep($step)
    {
        $this->doneSteps[] = $step;
        $this->fullDoneSteps[] = $step;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDoneStep($step)
    {
        return in_array($step, $this->doneSteps);
    }

    /**
     * {@inheritdoc}
     */
    public function retraceDoneStep($step)
    {
        $remove = false;
        $removedSteps = array();

        foreach ($this->doneSteps as $i => $name) {
            if (!$remove && $step === $name) {
                $remove = true;
            }

            if ($remove) {
                $removedSteps[] = $name;
                unset($this->doneSteps[$i]);
            }
        }

        $this->doneSteps = array_values($this->doneSteps);

        return $removedSteps;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCanceledStep($step)
    {
        return !$this->hasDoneStep($step)
            && in_array($step, $this->fullDoneSteps)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function addTakenPath($path)
    {
        $this->takenPaths[] = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTakenPath($path)
    {
        return in_array($path, $this->takenPaths);
    }

    /**
     * {@inheritdoc}
     */
    public function retraceTakenPath($path)
    {
        $remove = false;
        $removedPaths = array();

        foreach ($this->takenPaths as $i => $name) {
            if (!$remove && $path === $name) {
                $remove = true;
            }

            if ($remove) {
                $removedPaths[] = $name;
                unset($this->takenPaths[$i]);
            }
        }

        $this->takenPaths = array_values($this->takenPaths);

        return $removedPaths;
    }
}
