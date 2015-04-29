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
     */
    public function __construct(
        FormFactoryInterface       $formFactory,
        Request                    $request,
        MapInterface               $map,
        array                      $data = array(),
        FlowDataStoreInterface     $flowDataStore,
        NavigationLoggerInterface  $logger = null
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
        $this->hasNavigated  = false;
        $this->hasReturned   = false;
        $this->hasFinished   = false;

        if ($this->logger) {
            $this->logger->startNavigation();
        }

        $this->initFlow();
        $this->getForm();
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
        $this->flow = $this->flowDataStore->get(
            $this->map,
            $this->request
        );

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

                $this->save();
            }
        }
    }

    /**
     * Returns the navigation form builder.
     *
     * @return Symfony\Component\Form\FormBuilderInterface
     */
    protected function getFormBuilder()
    {
        $data = $this->getCurrentStepData();

        return $this->formFactory->createBuilder(
            'idci_step_navigator',
            !empty($data) ? array('_data' => $data) : null,
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
     * Retrieve the choosen path.
     *
     * @return PathInterface
     */
    protected function getChosenPath()
    {
        foreach ($this->getAvailablePaths() as $i => $path) {
            if ($this->getForm()->get(sprintf('_path_%d', $i))->isClicked()) {
                $this->getFlow()->takePath($path, $i);

                return $path;
            }
        }

        throw new \LogicException(sprintf(
            'The taken path seems to disapear magically'
        ));
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
        return $this->getMap()->getStep($this->getFlow()->getCurrentStepName());
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
