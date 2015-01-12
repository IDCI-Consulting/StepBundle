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
use IDCI\Bundle\StepBundle\Flow\DataStore\FlowDataStoreInterface;

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
        $this->formFactory  = $formFactory;

        parent::__construct(
            $map,
            $request,
            $flowDataStore,
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
    protected function getChosenPath()
    {
        $form = $this->getForm();
        $flow = $this->getFlow();

        if ($form->has('_data')) {
            $flow->setStepData(
                $flow->getCurrentStep(),
                $form->get('_data')->getData()
            );
        }

        foreach ($this->getAvailablePaths() as $i => $path) {
            if ($form->get(sprintf('_path#%d', $i))->isClicked()) {
                $flow->takePath($path, $i);

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
    protected function doNavigation()
    {
        $destinationStep = null;
        $form = $this->getForm();

        if ($form->has('_back') && $form->get('_back')->isClicked()) {
            $flow = $this->getFlow();
            $previousStep = $this
                ->getMap()
                ->getStep($flow->getPreviousStepName())
            ;

            $flow->retraceTo($previousStep);

            $destinationStep = $previousStep;
        } else {
            $path = $this->getChosenPath();
            $destinationStep = $path->resolveDestination($this);

            if (null === $destinationStep) {
                $this->hasFinished = true;
            }
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
    public function createStepView()
    {
        return $this->getForm()->createView();
    }
}