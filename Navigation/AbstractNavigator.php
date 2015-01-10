<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\HttpFoundation\Request;
use IDCI\Bundle\StepBundle\DataStore\DataStoreInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Flow\Flow;
use IDCI\Bundle\StepBundle\Flow\DataStore\FlowDataStoreInterface;

abstract class AbstractNavigator implements NavigatorInterface
{
    /**
     * @var MapInterface
     */
    protected $map;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var FlowDataStoreInterface
     */
    protected $flowDataStore;

    /**
     * @var NavigationLoggerInterface
     */
    protected $logger;

    /**
     * @var FlowInterface
     */
    protected $flow;

    /**
     * @var boolean
     */
    protected $hasNavigated;

    /**
     * @var boolean
     */
    protected $hasFinished;

    /**
     * Constructor
     *
     * @param MapInterface               $map               The map to navigate.
     * @param Request                    $request           The HTTP request.
     * @param FlowDataStoreInterface     $flowDataStore     The flow data store using to keep the flow.
     * @param NavigationLoggerInterface  $logger            The logger.
     */
    public function __construct(
        MapInterface               $map,
        Request                    $request,
        FlowDataStoreInterface     $flowDataStore,
        NavigationLoggerInterface  $logger = null
    )
    {
        $this->map               = $map;
        $this->request           = $request;
        $this->flowDataStore     = $flowDataStore;
        $this->logger            = $logger;
        $this->hasNavigated      = false;
        $this->hasFinished       = false;

        if ($logger) {
            $this->logger->startNavigation();
        }

        $this->navigate();

        if ($logger) {
            $this->logger->stopNavigation($this);
        }
    }

    /**
     * Returns the navigator name.
     *
     * @return string
     */
    public static function getName()
    {
        return 'idci_step_navigator';
    }

    /**
     * {@inheritdoc}
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * {@inheritdoc}
     */
    public function getFlow()
    {
        if (null === $this->flow) {
            $this->flow = $this->flowDataStore->get(
                $this->map->getFingerPrint(),
                $this->request
            );

            if (null === $this->flow) {
                $this->flow = new Flow();
                $this->flow->setCurrentStep($this->map->getFirstStepName());
            }
        }

        return $this->flow;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentStep()
    {
        return $this->getMap()->getStep($this->getFlow()->getCurrentStep());
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviousStep()
    {
        return $this->getMap()->getStep($this->getFlow()->getPreviousStep());
    }

    /**
     * Get the destination step.
     *
     * @return StepInterface|null The destination step.
     */
    abstract protected function getDestinationStep();

    /**
     * Returns the current step data.
     *
     * @return array The data.
     */
    protected function getCurrentStepData()
    {
        $data = array();

        $currentStepName = $this->getFlow()->getCurrentStep();
        if ($this->getFlow()->getData()->hasStepData($currentStepName)) {
            $data = $this->getFlow()->getData()->getStepData($currentStepName);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailablePaths()
    {
        return $this->getMap()->getPaths($this->getFlow()->getCurrentStep());
    }

    /**
     * {@inheritdoc}
     */
    public function hasNavigated()
    {
        return $this->hasNavigated;
    }

    /**
     * {@inheritdoc}
     */
    public function hasFinished()
    {
        return $this->hasFinished;
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $this->flowDataStore->set(
            $this->map->getFingerPrint(),
            $this->request,
            $this->flow
        );
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->flowDataStore->clear(
            $this->map->getFingerPrint(),
            $this->request
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function navigate()
    {
        $flow = $this->getFlow();
        $currentStep = $this->map->getStep($flow->getCurrentStep());

        if ($this->request->isMethod('POST')) {
            $destinationStep = $this->getDestinationStep();

            if (null !== $destinationStep) {
                $takenPath = $this->getTakenPath();
                $this->hasNavigated = true;
                $flow->setCurrentStep($destinationStep->getName());
                $this->save();
            }
        }
    }
}