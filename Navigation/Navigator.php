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
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Flow\FlowEventNotifierInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\DataStore\DataStoreInterface;

class Navigator extends AbstractNavigator
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
     * The taken path.
     *
     * @var PathInterface|null
     */
    protected $takenPath = false;

    /**
     * The destination step.
     *
     * @var StepInterface|null
     */
    protected $destinationStep = false;

    /**
     * Constructor
     *
     * @param FormFactoryInterface       $formFactory       The form factory.
     * @param DataStoreInterface         $dataStore         The data store using to keep the flow.
     * @param MapInterface               $map               The map to navigate.
     * @param Request                    $request           The HTTP request.
     * @param FlowEventNotifierInterface $flowEventNotifier The flow event notifier.
     * @param NavigationLoggerInterface  $logger            The logger.
     */
    public function __construct(
        FormFactoryInterface       $formFactory,
        DataStoreInterface         $dataStore,
        MapInterface               $map,
        Request                    $request,
        FlowEventNotifierInterface $flowEventNotifier,
        NavigationLoggerInterface  $logger = null
    )
    {
        $this->formFactory  = $formFactory;

        parent::__construct(
            $dataStore,
            $map,
            $request,
            $flowEventNotifier,
            $logger
        );
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
            new NavigatorType(),
            !empty($data) ? array('_data' => $data) : null,
            array('navigator' => $this)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getTakenPath()
    {
        if (false === $this->takenPath) {
            $this->takenPath = null;
            $form = $this->getForm();

            if (
                $form->isValid() &&
                (!$form->has('_back') || !$form->get('_back')->isClicked())
            ) {
                $flow = $this->getFlow();

                if ($form->has('_data')) {
                    $flow->getData()->setStepData(
                        $flow->getCurrentStep(),
                        $form->get('_data')->getData()
                    );
                }

                foreach ($this->getAvailablePaths() as $i => $path) {
                    if ($form->get(sprintf('_path#%d', $i))->isClicked()) {
                        $flow->getHistory()->addTakenPath($path->getSource(), $i);

                        return $path;
                    }
                }

                throw new \LogicException(sprintf(
                    'The taken path seem to disapear magically'
                ));
            }
        }

        return $this->takenPath;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDestinationStep()
    {
        if (false === $this->destinationStep) {
            $this->destinationStep = null;
            $form = $this->getForm();

            if ($form->has('_back') && $form->get('_back')->isClicked()) {
                $flow = $this->getFlow();
                $previousStep = $this
                    ->getMap()
                    ->getStep($flow->getPreviousStep())
                ;
                $flow->getHistory()->retraceTakenPath($previousStep);

                $this->destinationStep = $previousStep;
            } else {
                $path = $this->getTakenPath();

                if (null !== $path) {
                    $this->destinationStep = $path->resolveDestination($this);

                    if (null === $this->destinationStep) {
                        $this->hasFinished = true;
                    }
                }
            }
        }

        return $this->destinationStep;
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
            $this->form->handleRequest($this->request);
        }

        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function createStepView()
    {
        return $this->getForm()->createView();
    }
}