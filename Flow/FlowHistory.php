<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Step\Step;

class FlowHistory implements FlowHistoryInterface
{
    /**
     * The taken paths.
     *
     * @var array
     */
    protected $takenPaths = array();

    /**
     * The full taken paths.
     *
     * @var array
     */
    protected $fullTakenPaths = array();

    /**
     * {@inheritdoc}
     */
    public function addTakenPath(StepInterface $step, $pathId = 0)
    {
        $stepName = $path->getName();

        $this->takenPaths[] = array(
            'step' => $stepName,
            'path' => $pathId
        );
        $this->fullTakenPaths[] = array(
            'step' => $stepName,
            'path' => $pathId
        );
    }

    /**
     * {@inheritdoc}
     */
    public function retraceTakenPath(StepInterface $step)
    {
        $stepName = $step->getName();

        $this->fullTakenPaths[] = array(
            'step' => $stepName,
            'path' => '__back'
        );

        $remove = false;
        $removedPaths = array();

        foreach ($this->takenPaths as $i => $path) {
            if (!$remove && $stepName === $path['step']) {
                $remove = true;
            }

            if ($remove) {
                $removedPaths[$name] = $path;
                unset($this->takenPaths[$i]);
            }
        }

        return $removedPaths;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastTakenPath()
    {
        return $this->takenPaths[count($this->takenPaths) - 1];
    }

    /**
     * {@inheritdoc}
     */
    public function getTakenPaths()
    {
        return $this->takenPaths;
    }

    /**
     * {@inheritdoc}
     */
    public function getFullTakenPaths()
    {
        return $this->fullTakenPaths;
    }
}
