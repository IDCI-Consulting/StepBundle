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
     * Constructor
     *
     * @param FormFactoryInterface      $formFactory    The form factory.
     * @param DataStoreInterface        $dataStore      The data store using to keep the flow.
     * @param MapInterface              $map            The map to navigate.
     * @param Request                   $request        The HTTP request.
     * @param NavigationLoggerInterface $logger         The logger.
     */
    public function __construct(
        FormFactoryInterface      $formFactory,
        DataStoreInterface        $dataStore,
        MapInterface              $map,
        Request                   $request,
        NavigationLoggerInterface $logger = null
    )
    {
        $this->formFactory  = $formFactory;

        parent::__construct(
            $dataStore,
            $map,
            $request,
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

            if ($form->isValid()) {
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
                    'The chosen path seem to disapear magically'
                ));
            }
        }

        return $this->takenPath;
    }

    /**
     * Retrieve the destination.
     *
     * @param FormInterface $form The form.
     * @param FlowInterface $flow The flow.
     *
     * @return StepInterface|null The reached destination step.
     */
    protected function retrieveDestination(FormInterface $form, FlowInterface $flow)
    {
        if ($form->has('_back') && $form->get('_back')->isClicked()) {
            $previousStep = $this
                ->getMap()
                ->getStep($flow->getPreviousStep())
            ;
            $flow->getHistory()->retraceTakenPath($previousStep);

            return $previousStep;
        }

        $path = $this->getTakenPath($form, $flow);

        if (null === $path) {
            return null;
        }

        $destinationStep = $path->resolveDestination($this);

        if (null === $destinationStep) {
            $this->hasFinished = true;

            return null;
        }

        return $destinationStep;
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
    protected function navigate()
    {
        if ($this->request->isMethod('POST')) {
            $form = $this->getForm();
            $flow = $this->getFlow();
            $destination = $this->retrieveDestination($form, $flow);

            if (null !== $destination) {
                $this->hasNavigated = true;
                $flow->setCurrentStep($destination->getName());
                $this->save();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createStepView()
    {
        return $this->getForm()->createView();
    }
}