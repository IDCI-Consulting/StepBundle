<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

use IDCI\Bundle\StepBundle\Step\StepInterface;

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
        $this->takenPaths[] = array(
            'source' => $step->getName(),
            'index'  => $pathId
        );
        $this->fullTakenPaths[] = array(
            'source' => $step->getName(),
            'index'  => $pathId
        );
    }

    /**
     * {@inheritdoc}
     */
    public function retraceTakenPath(StepInterface $step)
    {
        $this->fullTakenPaths[] = array(
            'source' => $step->getName(),
            'index'  => '_back'
        );

        $remove = false;
        $removedPaths = array();

        foreach ($this->takenPaths as $i => $path) {
            if (!$remove && $step->getName() === $path['source']) {
                $remove = true;
            }

            if ($remove) {
                $removedPaths[$step->getName()] = $path;
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
        $i = count($this->takenPaths) - 1;

        return isset($this->takenPaths[$i]) ? $this->takenPaths[$i] : null;
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
