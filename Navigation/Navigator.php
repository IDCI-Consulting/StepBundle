<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use IDCI\Bundle\StepBundle\Flow\Flow;
use IDCI\Bundle\StepBundle\Step\StepInterface;

class Navigator extends AbstractNavigator
{
    /**
     * @var Symfony\Component\Form\FormBuilderInterface
     */
    protected $formBuilder = null;

    /**
     * @var Symfony\Component\Form\FormInterface
     */
    protected $form = null;

    /**
     * Returns the navigation form builder.
     *
     * @return Symfony\Component\Form\FormBuilderInterface
     */
    protected function getFormBuilder()
    {
        if (null === $this->formBuilder) {
            $this->formBuilder = $this->formFactory->createBuilder(
                new NavigatorType(),
                null,
                array('navigator' => $this)
            );
        }

        return $this->formBuilder;
    }

    /**
     * Returns the navigation form.
     *
     * @return Symfony\Component\Form\FormInterface
     */
    protected function getForm()
    {
        if (null === $this->form) {
            $this->form = $this->getFormBuilder()->getForm();
        }

        return $this->form;
    }

    /**
     * Resets form.
     */
    private function resetForm()
    {
        $this->formBuilder = null;
        $this->form        = null;
    }

    /**
     * Init the flow.
     */
    protected function initFlow()
    {
        $this->flow = $this->retrieveFlow();

        if ($this->request->isMethod('POST')) {
            $this->getForm()->handleRequest($this->request);
            if ($this->getForm()->isValid()) {
                $path = $this->getChoosenPath();
                $this->moveTo($path->resolveDestination($this));
            }
        }
    }

    /**
     * Retrieve the flow.
     *
     * @return FlowInterface
     */
    protected function retrieveFlow()
    {
        $flow = $this->dataStore->get(
            $this->map->getFingerPrint(),
            'flow'
        );

        if (null === $flow) {
            $flow = new Flow();
            $flow->setCurrentStep($this->map->getFirstStepName());
        }

        return $flow;
    }

    /**
     * Save the flow.
     */
    protected function saveFlow()
    {
        $this->dataStore->set(
            $this->map->getFingerPrint(),
            'flow',
            $this->flow
        );
    }

    /**
     * Returns the choosen path.
     *
     * @return PathInterface
     */
    private function getChoosenPath()
    {
        foreach ($this->getAvailablePaths() as $i => $path) {
            if ($this->getForm()->get(sprintf('_path#%d', $i))->isClicked()) {
                return $path;
            }
        }

        throw new \LogicException(sprintf(
            'The choosen path seem to disapear magically'
        ));
    }

    /**
     * Move to the given step destination.
     *
     * @param StepInterface $destination The step destination.
     */
    private function moveTo(StepInterface $destination = null)
    {
        $this->resetForm();

        if (null === $destination) {
            $this->hasFinished = true;
        } else {
            $this->flow->setCurrentStep($destination->getName());
            $this->saveFlow();
            $this->isMoving = true;
        }

    }
}