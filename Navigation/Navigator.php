<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormFactoryInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\DeserializationContext;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Flow\Flow;
use IDCI\Bundle\StepBundle\Flow\FlowData;
use IDCI\Bundle\StepBundle\Flow\DataStore\FlowDataStoreInterface;

class Navigator implements NavigatorInterface
{
    /**
     * The current form.
     *
     * @var FormInterface
     */
    protected $form;

    /**
     * The current form view.
     *
     * @var FormViewInterface
     */
    protected $formView;

    /**
     * The form factory.
     *
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var MapInterface
     */
    protected $map;

    /**
     * @var array
     */
    protected $data;

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
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var FlowInterface
     */
    protected $flow;

    /**
     * @var StepInterface
     */
    protected $currentStep;

    /**
     * @var PathInterface
     */
    protected $chosenPath;

    /**
     * @var boolean
     */
    protected $hasNavigated;

    /**
     * @var boolean
     */
    protected $hasReturned;

    /**
     * @var boolean
     */
    protected $hasFinished;

    /**
     * Constructor
     *
     * @param FormFactoryInterface       $formFactory       The form factory.
     * @param Request                    $request           The HTTP request.
     * @param MapInterface               $map               The map to navigate.
     * @param array                      $data              The navigation data.
     * @param FlowDataStoreInterface     $flowDataStore     The flow data store using to keep the flow.
     * @param NavigationLoggerInterface  $logger            The logger.
     * @param SerializerInterface        $serializer        The serializer.
     */
    public function __construct(
        FormFactoryInterface       $formFactory,
        Request                    $request,
        MapInterface               $map,
        array                      $data = array(),
        FlowDataStoreInterface     $flowDataStore,
        NavigationLoggerInterface  $logger = null,
        SerializerInterface        $serializer
    )
    {
        $this->form          = null;
        $this->formView      = null;
        $this->formFactory   = $formFactory;
        $this->request       = $request;
        $this->map           = $map;
        $this->data          = array_replace_recursive($this->map->getData(), $data);
        $this->flowDataStore = $flowDataStore;
        $this->logger        = $logger;
        $this->serializer    = $serializer;
        $this->flow          = null;
        $this->currentStep   = null;
        $this->chosenPath    = null;
        $this->hasNavigated  = false;
        $this->hasReturned   = false;
        $this->hasFinished   = false;

        if ($this->logger) {
            $this->logger->startNavigation();
        }

        $this->initFlow();
        $this->prepareCurrentStep();
        $this->navigate();

        if ($this->logger) {
            $this->logger->stopNavigation($this);
        }
    }

    /**
     * Init the flow
     */
    protected function initFlow()
    {
        $serializedFlow = $this->flowDataStore->get(
            $this->map,
            $this->request
        );

        if (null === $serializedFlow) {
            $this->flow = new Flow();
            $this->flow->setCurrentStep($this->map->getFirstStep());

            if (!empty($this->data)) {
                foreach ($this->data as $stepName => $stepData) {
                    $this->flow->setStepData(
                        $this->map->getStep($stepName),
                        $stepData,
                        FlowData::TYPE_REMINDED
                    );
                }
            }

            $this->save();
        } else {
            $this->flow = $this->unserialize($serializedFlow);
        }

        $this->reconstructFlowData();
    }

    /**
     * Prepare the current step
     */
    protected function prepareCurrentStep()
    {
        // Clone the map step into the navigation current step.
        $this->currentStep = clone $this->getMap()->getStep(
            $this->getFlow()->getCurrentStepName()
        );

        // Allow StepType to transform step options
        $this->currentStep->setOptions($this
            ->currentStep
            ->getType()
            ->prepareNavigation(
                $this,
                $this->currentStep->getOptions()
            )
        );

        // Build the step form
        $this->getForm();
    }

    /**
     * Returns the navigation form builder.
     *
     * @return Symfony\Component\Form\FormBuilderInterface
     */
    protected function getFormBuilder()
    {
        return $this->formFactory->createBuilder(
            'idci_step_navigator',
            null,
            array('navigator' => $this)
        );
    }

    /**
     * Returns the navigation form.
     *
     * @return FormInterface The form.
     */
    protected function getForm()
    {
        if (null === $this->form) {
            $this->form = $this->getFormBuilder()->getForm();
        }

        return $this->form;
    }

    /**
     * Transform data
     *
     * @param mixed $data    The data to transform.
     * @param array $mapping The mapping, containing the expected data type and optionnaly the serialization groups.
     *
     * @return The transformed data.
     */
    protected function transformData($data, array $mapping)
    {
        if (gettype($data) === $mapping['type'] || $data instanceof $mapping['type']) {
            return $data;
        }

        $context = new DeserializationContext();
        if (!empty($mapping['groups'])) {
            $context->setGroups($mapping['groups']);
        }

        return $this->serializer->deserialize(
            json_encode($data),
            $mapping['type'],
            'json',
            $context
        );
    }

    /**
     * Transform flow data if a step data type mapping is defined
     */
    public function reconstructFlowData()
    {
        foreach ($this->map->getSteps() as $stepName => $step) {
            if ($this->flow->hasStepData($step)) {
                $mapping = $step->getDataTypeMapping();
                $data = $this->flow->getStepData($step);
                $transformed = array();

                foreach ($data as $field => $value) {
                    if (null === $value || (is_array($value) && empty($value))) {
                        continue;
                    }

                    if (isset($mapping[$field])) {
                        $transformed[$field] = $this->transformData($value, $mapping[$field]);
                    }
                }

                if (!empty($transformed)) {
                    $this->flow->setStepData(
                        $step,
                        array_replace_recursive($data, $transformed)
                    );
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function navigate()
    {
        if ($this->hasNavigated() || $this->hasReturned() || $this->hasFinished()) {
            throw new \LogicException('The navigation has already been done');
        }

        if ($this->request->isMethod('POST')) {
            $form = $this->getForm();
            $form->handleRequest($this->request);

            if (!$this->hasReturned() && $form->isValid()) {
                $path = $this->getChosenPath();
                if (null === $path) {
                    throw new \LogicException(sprintf(
                        'The taken path seems to disapear magically'
                    ));
                }
                $destinationStep = $path->resolveDestination($this);

                if (null === $destinationStep) {
                    $this->hasFinished = true;
                } else {
                    $this->hasNavigated = true;
                    $this->getFlow()->setCurrentStep($destinationStep);
                }

                $this->save();

                // Reset the current form.
                $this->form = null;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function goBack($stepName = null)
    {
        $destinationStep = $this->getPreviousStep($stepName);

        if (null === $destinationStep) {
            throw new \LogicException('Could not go back to a non existing step');
        }

        $this->getFlow()->retraceTo($destinationStep);
        $this->hasReturned = true;
        $this->save();

        // Reset the current form.
        $this->form = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        return $this->request;
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
        return $this->flow;
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
    public function getCurrentPaths()
    {
        return $this->getMap()->getPaths($this->getFlow()->getCurrentStepName());
    }

    /**
     * {@inheritdoc}
     */
    public function setChosenPath(PathInterface $path)
    {
        $this->chosenPath = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getChosenPath()
    {
        return $this->chosenPath;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviousStep($stepName = null)
    {
        if (null === $stepName) {
            $stepName = $this->getFlow()->getPreviousStepName();
            if (null === $stepName) {
                return null;
            }
        }

        $previousStep = $this->getMap()->getStep($stepName);

        if (null !== $previousStep && !$this->getFlow()->hasDoneStep($previousStep)) {
            throw new \LogicException(sprintf(
                'The step "%s" is not a previous step',
                $stepName
            ));
        }

        return $previousStep;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentStepData($type = null)
    {
        return $this->getFlow()->getStepData($this->getCurrentStep(), $type);
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrentStepData(array $data, $type = null)
    {
        $this->getFlow()->setStepData(
            $this->getCurrentStep(),
            $data,
            $type
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailablePaths()
    {
        return $this->getMap()->getPaths($this->getFlow()->getCurrentStepName());
    }

    /**
     * {@inheritdoc}
     */
    public function getTakenPaths()
    {
        return $this->getFlow()->getTakenPaths();
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
    public function hasReturned()
    {
        return $this->hasReturned;
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
    public function serialize()
    {
        return $this->serializer->serialize($this->flow, 'json');
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serializedFlow)
    {
        return $this->serializer->deserialize(
            $serializedFlow,
            'IDCI\Bundle\StepBundle\Flow\Flow',
            'json'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $this->flowDataStore->set(
            $this->map,
            $this->request,
            $this->serialize()
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

    /**
     * {@inheritdoc}
     */
    public function createStepView()
    {
        return $this->getForm()->createView();
    }

    /**
     * {@inheritdoc}
     */
    public function getFormView()
    {
        if (null === $this->formView) {
            $this->formView = $this->createStepView();
        }

        return $this->formView;
    }
}
