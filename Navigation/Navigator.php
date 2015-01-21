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
     * @param FormFactoryInterface       $formFactory       The form factory.
     * @param MapInterface               $map               The map to navigate.
     * @param Request                    $request           The HTTP request.
     * @param FlowDataStoreInterface     $flowDataStore     The flow data store using to keep the flow.
     * @param NavigationLoggerInterface  $logger            The logger.
     */
    public function __construct(
        FormFactoryInterface       $formFactory,
        MapInterface               $map,
        Request                    $request,
        FlowDataStoreInterface     $flowDataStore,
        NavigationLoggerInterface  $logger = null
    )
    {
        $this->formFactory   = $formFactory;
        $this->map           = $map;
        $this->request       = $request;
        $this->flowDataStore = $flowDataStore;
        $this->logger        = $logger;
        $this->hasNavigated  = false;
        $this->hasFinished   = false;

        $this->navigate();
    }

    /**
     * Returns the navigation form builder.
     *
     * @return Symfony\Component\Form\FormBuilderInterface
     */
    protected function getFormBuilder()
    {
        $data = $this->getCurrentNormalizedStepData();

        return $this->formFactory->createBuilder(
            new NavigatorType(),
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

            if ($this->request) {
                $this->form->handleRequest($this->request);
            }
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
            if ($this->getForm()->get(sprintf('_path#%d', $i))->isClicked()) {
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
        if ($this->hasNavigated()) {
            throw new \LogicException('The navigation has already been done');
        }

        if ($this->logger) {
            $this->logger->startNavigation();
        }

        if ($this->request->isMethod('POST')) {
            $destinationStep = null;
            if ($this->getForm()->has('_back') && $this->getForm()->get('_back')->isClicked()) {
                $previousStep = $this
                    ->getMap()
                    ->getStep($this->getFlow()->getPreviousStepName())
                ;

                $this->getFlow()->retraceTo($previousStep);

                $destinationStep = $previousStep;
            } else {
                $path = $this->getChosenPath();
                $destinationStep = $path->resolveDestination($this);

                if (null === $destinationStep) {
                    $this->hasFinished = true;
                }
            }

            if (null !== $destinationStep) {
                $this->hasNavigated = true;
                $this->getFlow()->setCurrentStep($destinationStep);
                $this->save();
            }
        }

        if ($this->logger) {
            $this->logger->stopNavigation($this);
        }

        if ($this->getForm()->isValid() && $this->hasNavigated) {
            // Reset the current form.
            $this->form = null;
            $this->request = null;
        }
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
                $this->map,
                $this->request
            );

            if (null === $this->flow) {
                $this->flow = new Flow();
                $this->flow->setCurrentStep($this->map->getFirstStep());
            }
        }

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
    public function getPreviousStep()
    {
        $previousStepName = $this->getFlow()->getPreviousStepName();

        return $previousStepName
            ? $this->getMap()->getStep($previousStepName)
            : null
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentStepData()
    {
        return $this->getFlow()->getStepData($this->getCurrentStep());
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrentStepData(array $data)
    {
        $this->getFlow()->setStepData(
            $this->getCurrentStep(),
            $data
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentNormalizedStepData()
    {
        $step = $this->getCurrentStep();
        $builder = $this->formFactory->createBuilder();
        $configuration = $step->getConfiguration();

        $step->getType()->buildNavigationStepForm(
            $builder,
            $configuration['options']
        );

        $form = $builder->getForm();

        if ($form->has('_data')) {
            $form->submit(array('_data' => $this->getCurrentStepData()));

            return $form->get('_data')->getData();
        }

        return array();
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
}