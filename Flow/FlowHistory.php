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
     * Constructor
     *
     * @param array $takenPaths     The taken paths.
     * @param array $fullTakenPaths The full taken paths.
     */
    public function __construct($takenPaths = array(), $fullTakenPaths = array())
    {
        $this->takenPaths     = $takenPaths;
        $this->fullTakenPaths = $fullTakenPaths;
    }

    /**
     * {@inheritdoc}
     */
    public function addTakenPath(StepInterface $step, $pathId = 0)
    {
        $this->takenPaths[] = array(
            'source'  => $step->getName(),
            'index'   => $pathId,
        );
        $this->fullTakenPaths[] = array(
            'source' => $step->getName(),
            'index'  => $pathId,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function retraceTakenPath(StepInterface $step)
    {
        $this->fullTakenPaths[] = array(
            'source' => $step->getName(),
            'index'  => '_back',
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

    /**
     * {@inheritdoc}
     */
    public function hasDoneStep(StepInterface $step, $full = false)
    {
        $takenPaths = (bool)$full ? $this->fullTakenPaths : $this->takenPaths;
        $stepName = $step->getName();

        foreach ($takenPaths as $takenPath) {
            if ($takenPath['source'] === $stepName) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        return array(
            'takenPaths'     => $this->takenPaths,
            'fullTakenPaths' => $this->fullTakenPaths,
        );
    }
}
