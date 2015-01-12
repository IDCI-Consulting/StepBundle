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

        $this->navigate();
    }

    /**
     * {@inheritdoc}
     */
    public function navigate()
    {
        if ($this->hasNavigated()) {
            throw new \LogicException('The navigation has already been done');
        }

        if ($this->logger) {
            $this->logger->startNavigation();
        }

        if ($this->request->isMethod('POST')) {
            $destinationStep = $this->doNavigation();

            if (null !== $destinationStep) {
                $this->hasNavigated = true;
                $this->getFlow()->setCurrentStep($destinationStep);
                $this->save();
            }
        }

        if ($this->logger) {
            $this->logger->stopNavigation($this);
        }
    }

    /**
     * Do the navigation to the chosen destination step.
     *
     * @return StepInterface|null The destination step.
     */
    abstract protected function doNavigation();

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
                $this->map,
                $this->request
            );

            if (null === $this->flow) {
                $this->flow = new Flow();
                $this->flow->setCurrentStep($this->map->getFirstStep());
            }
        var_dump($this->flow->getCurrentStep()->getName());
        }

        return $this->flow;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentStep()
    {
        return $this->getFlow()->getCurrentStep();
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviousStep()
    {
        $previousStepName = $this->getFlow()->getPreviousStepName();

        return $previousStepName
            ? $this->getMap()->getStep($previousStepName)
            : null
        ;
    }

    /**
     * Returns the current step data.
     *
     * @return array The data.
     */
    public function getCurrentStepData()
    {
        return  $this->getFlow()->getStepData($this->getCurrentStep());
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailablePaths()
    {
        return $this->getMap()->getPaths($this->getFlow()->getCurrentStep()->getName());
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
            $this->map,
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
            $this->map,
            $this->request
        );
    }
}