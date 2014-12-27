<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use IDCI\Bundle\StepBundle\Flow\Flow;
use IDCI\Bundle\StepBundle\Path\PathInterface;

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
            $destination = $this->navigate();
            if (null !== $destination) {
                $this->hasNavigated = true;
                $this->flow->setCurrentStep($destination->getName());
                $this->saveFlow();
            }
            $this->resetForm();
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
     * @return PathInterface | null
     */
    private function getChoosenPath()
    {
        if ($this->getForm()->isValid()) {
            foreach ($this->getAvailablePaths() as $i => $path) {
                if ($this->getForm()->get(sprintf('_path#%d', $i))->isClicked()) {
                    $this->flow->getHistory()->addTakenPath($path->getSource(), $i);

                    return $path;
                }
            }

            throw new \LogicException(sprintf(
                'The choosen path seem to disapear magically'
            ));
        }

        return null;
    }

    /**
     * Navigate using the given path.
     *
     * @return StepInterface | null The reached step.
     */
    private function navigate()
    {
        if ($this->getForm()->has('_back') && $this->getForm()->get('_back')->isClicked()) {
            $lastTakenPath = $this->flow->getHistory()->getLastTakenPath();
            $previousStep = $this->getMap()->getStep($lastTakenPath['source']);
            $this->flow->getHistory()->retraceTakenPath($previousStep);

            return $previousStep;
        }

        $path = $this->getChoosenPath();

        if (null === $path) {
            return null;
        }

        $destination = $path->resolveDestination($this);

        if (null === $destination) {
            $this->hasFinished = true;

            return null;
        }

        return $destination;
    }
}