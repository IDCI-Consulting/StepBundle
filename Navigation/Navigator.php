<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

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
    private function getFormBuilder()
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
     * Resets form.
     */
    private function resetForm()
    {
        $this->formBuilder = null;
        $this->form        = null;
    }

    /**
     * Save the flow.
     */
    private function saveFlow()
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
     * Retrieve the destination.
     *
     * @return StepInterface | null The destination step to reached.
     */
    private function retrieveDestination()
    {
        if ($this->getForm()->has('_back') && $this->getForm()->get('_back')->isClicked()) {
            $lastTakenPath = $this->getFlow()->getHistory()->getLastTakenPath();
            $previousStep = $this->getMap()->getStep($lastTakenPath['source']);
            $this->getFlow()->getHistory()->retraceTakenPath($previousStep);

            return $previousStep;
        }

        $path = $this->getChoosenPath();

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
     * {@inheritdoc}
     */
    public function getForm()
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
        if ($this->request->isMethod('POST')) {
            $this->getForm()->handleRequest($this->request);
            $destination = $this->retrieveDestination();
            if (null !== $destination) {
                $this->hasNavigated = true;
                $this->getFlow()->setCurrentStep($destination->getName());
                $this->saveFlow();
            }
            $this->resetForm();
        }
    }
}