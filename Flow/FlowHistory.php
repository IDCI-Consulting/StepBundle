<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
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
    public function setCurrentStep(StepInterface $step)
    {
        if (empty($this->fullTakenPaths)) {
            $path = array(
                'source'      => null,
                'index'       => null,
                'destination' => $step->getName(),
            );
            $this->takenPaths[]     = $path;
            $this->fullTakenPaths[] = $path;
        } else {
            $this->takenPaths[count($this->takenPaths) -1]['destination'] = $step->getName();
            $this->fullTakenPaths[count($this->fullTakenPaths) -1]['destination'] = $step->getName();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addTakenPath(StepInterface $step, $pathId = 0)
    {
        $path = array(
            'source'  => $step->getName(),
            'index'   => $pathId,
        );
        $this->takenPaths[]     = $path;
        $this->fullTakenPaths[] = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function retraceTakenPath($sourceStepName, StepInterface $destinationStep)
    {
        $this->fullTakenPaths[] = array(
            'source'      => $sourceStepName,
            'index'       => '_back',
        );

        $removedPaths = array();

        $reversedIndex = count($this->takenPaths) - 1;
        foreach (array_reverse($this->takenPaths) as $i => $path) {
            $removedPaths[] = $this->takenPaths[$reversedIndex - $i];
            unset($this->takenPaths[$reversedIndex - $i]);

            if ($destinationStep->getName() == $path['source']) {
                break;
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
        foreach ($takenPaths as $takenPath) {
            if (isset($takenPath['source']) && $takenPath['source'] == $step->getName()) {
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
