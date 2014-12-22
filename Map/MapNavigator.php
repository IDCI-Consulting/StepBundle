<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use Symfony\Component\Form\FormFactoryInterface;
use IDCI\Bundle\StepBundle\Form\MapStepType;
use IDCI\Bundle\StepBundle\Flow\FlowProviderInterface;
use IDCI\Bundle\StepBundle\Flow\DataStore\FlowDataStoreInterface;
use IDCI\Bundle\StepBundle\Exception\InvalidDestinationException;
use IDCI\Bundle\StepBundle\Exception\WrongStepRequestedException;

class MapNavigator implements MapNavigatorInterface
{
    /**
     * The flow provider.
     *
     * @var FlowDataStoreInterface
     */
    protected $flowProvider;

    /**
     * Constructor.
     *
     * @param FlowProviderInterface $flowProvider The flow provider.
     * @param FormFactoryInterface  $formFactory  The form factory.
     */
    public function __construct(
        FlowProviderInterface $flowProvider,
        FormFactoryInterface $formFactory
    )
    {
        $this->flowProvider = $flowProvider;
        $this->formFactory  = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataStore(FlowDataStoreInterface $dataStore)
    {
        $this->flowProvider->setDataStore($dataStore);
    }

    /**
     * {@inheritdoc}
     */
    public function navigate(MapInterface $map, $stepName)
    {
        // TODO: Check if the step is accessible from the current step
        return $map->getStep($stepName);
        // WTF
        /*
        $mapName = $map->getName();
        $currentStep = $flowDescriptor->getCurrentStep();

        // Handle POST request.
        if (null !== $data) {
            $currentStep = $flowDescriptor->getCurrentStep();
            $isValidDestination = false;
            $paths = $map->getPaths($currentStep);

            // TODO: Use has and get Destination on Path !
            foreach ($paths as $path) {
                $destinations = $path->getDestinations();

                foreach ($destinations as $pathDestination) {
                    if ($destination === $pathDestination) {
                        $isValidDestination = true;
                        break 2;
                    }
                }
            }

            if (!$isValidDestination) {
                throw new InvalidDestinationException($currentStep, $destination);
            }
        // Handle GET request to a step without having done the previous steps.
        } else if ($currentStep && $destination !== $currentStep) {
            throw new WrongStepRequestedException($destination, $currentStep);
        }

        $flowDescriptor = $this->flowProvider->retrieveFlowDescriptor($mapName);

        // If the step has already been done, retrace to this step.
        if ($flowDescriptor->hasDoneStep($currentStep)) {
            $flowDescriptor->retraceDoneStep($currentStep);
        }

        if (null !== $data) {
            // Add the current step to the list of done steps.
            $flowDescriptor->addDoneStep($currentStep);
        }
        // Set the destination as the current step.
        $flowDescriptor->setCurrentStep($destination);

        $this->flowProvider->retrieveFlowDescriptor($mapName, $flowDescriptor);

        if (null !== $data) {
            // Set the current step data in the flow.
            $dataFlow = $this->flowProvider->retrieveDataFlow($mapName);
            $dataFlow->setStep($currentStep, $data);
            $this->flowProvider->persistDataFlow($mapName, $dataFlow);
        }

        // Return the view for the destination step.
        return $map->createView($destination);
        */
    }

    /**
     * {@inheritdoc}
     */
    public function createStepFormBuilder(MapStepType $type)
    {
        return $this->formFactory->createBuilder($type);
    }
}
