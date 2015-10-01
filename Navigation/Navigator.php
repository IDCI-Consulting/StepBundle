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
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface;
use IDCI\Bundle\StepBundle\Flow\Flow;
use IDCI\Bundle\StepBundle\Flow\FlowData;

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
     * @var FlowRecorderInterface
     */
    protected $flowRecorder;

    /**
     * @var MapInterface
     */
    protected $map;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var NavigationLoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $data;

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
     * @param FormFactoryInterface      $formFactory  The form factory.
     * @param FlowRecorderInterface     $flowRecorder The flow recorder using to store the flow.
     * @param MapInterface              $map          The map to navigate.
     * @param Request                   $request      The HTTP request.
     * @param NavigationLoggerInterface $logger       The logger.
     * @param array                     $data         The navigation data.
     */
    public function __construct(
        FormFactoryInterface      $formFactory,
        FlowRecorderInterface     $flowRecorder,
        MapInterface              $map,
        Request                   $request,
        NavigationLoggerInterface $logger = null,
        array                     $data = array()
    )
    {
        $this->form         = null;
        $this->formView     = null;
        $this->formFactory  = $formFactory;
        $this->flowRecorder = $flowRecorder;
        $this->map          = $map;
        $this->request      = $request;
        $this->logger       = $logger;
        $this->data         = array_replace_recursive($this->map->getData(), $data);
        $this->flow         = null;
        $this->currentStep  = null;
        $this->chosenPath   = null;
        $this->hasNavigated = false;
        $this->hasReturned  = false;
        $this->hasFinished  = false;
    }

    /**
     * Init the flow
     */
    protected function initFlow()
    {
        $this->flow = $this->flowRecorder->getFlow(
            $this->map,
            $this->request
        );

        // The first time
        if (null === $this->flow) {
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

            $this->flowRecorder->reconstructFlowData($this->map, $this->flow);
            $this->save();
        }
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
     * {@inheritdoc}
     */
    public function navigate()
    {
        if ($this->logger) {
            $this->logger->startNavigation();
        }

        $this->initFlow();
        $this->prepareCurrentStep();

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

        if ($this->logger) {
            $this->logger->stopNavigation($this);
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
        return $this->flowRecorder->serialize($this->getFlow());
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $this->flowRecorder->setFlow(
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
        $this->flowRecorder->clearFlow(
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
