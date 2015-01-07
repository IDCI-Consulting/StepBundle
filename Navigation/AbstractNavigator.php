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
use IDCI\Bundle\StepBundle\Flow\FlowEventNotifierInterface;

abstract class AbstractNavigator implements NavigatorInterface
{
    /**
     * @var DataStoreInterface
     */
    protected $dataStore;

    /**
     * @var MapInterface
     */
    protected $map;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var FlowEventNotifierInterface
     */
    protected $flowEventNotifier;

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
     * @param DataStoreInterface         $dataStore         The data store using to keep the flow.
     * @param MapInterface               $map               The map to navigate.
     * @param Request                    $request           The HTTP request.
     * @param FlowEventNotifierInterface $flowEventNotifier The flow event notifier.
     * @param NavigationLoggerInterface  $logger            The logger.
     */
    public function __construct(
        DataStoreInterface         $dataStore,
        MapInterface               $map,
        Request                    $request,
        FlowEventNotifierInterface $flowEventNotifier,
        NavigationLoggerInterface  $logger = null
    )
    {
        $this->dataStore         = $dataStore;
        $this->map               = $map;
        $this->request           = $request;
        $this->flowEventNotifier = $flowEventNotifier;
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
            $this->flow = $this->dataStore->get(
                $this->map->getFingerPrint(),
                'flow'
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
        $this->dataStore->set(
            $this->map->getFingerPrint(),
            'flow',
            $this->flow
        );
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->dataStore->clear($this->map->getFingerPrint());
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
                // Notify event after step.
                if (null !== $currentStep) {
                    $currentStepConfiguration = $currentStep->getConfiguration();
                    $this->flowEventNotifier->notify(
                        $currentStepConfiguration['options']['listeners']['after'],
                        $flow
                    );
                }

                // Notify event before path.
                $takenPath = $this->getTakenPath();
                if (null !== $takenPath) {
                    $takenPathConfiguration = $takenPath->getConfiguration();
                    $this->flowEventNotifier->notify(
                        $takenPathConfiguration['options']['listeners']['before'],
                        $flow
                    );
                }

                $this->hasNavigated = true;
                $flow->setCurrentStep($destinationStep->getName());

                // Notify event after path.
                if (null !== $takenPath) {
                    $takenPathConfiguration = $takenPath->getConfiguration();
                    $this->flowEventNotifier->notify(
                        $takenPathConfiguration['options']['listeners']['after'],
                        $flow
                    );
                }

                // Notify event before step.
                $destinationStepConfiguration = $destinationStep->getConfiguration();
                $this->flowEventNotifier->notify(
                    $destinationStepConfiguration['options']['listeners']['before'],
                    $flow
                );

                $this->save();
            }
        } else if (
            $this->map->getFirstStepName() === $currentStep->getName() &&
            !$flow->getHistory()->hasDoneStep($currentStep)
        ) {
            // Notify event before step for the first step.
            $currentStepConfiguration = $currentStep->getConfiguration();
            $this->flowEventNotifier->notify(
                $currentStepConfiguration['options']['listeners']['before'],
                $flow
            );
        }
    }
}