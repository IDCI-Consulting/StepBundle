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
            $data = null;
            $currentStepName = $this->getFlow()->getCurrentStep();
            if ($this->getFlow()->getData()->hasStepData($currentStepName)) {
                $data = array(
                    '_data' => $this->getFlow()->getData()->getStepData($currentStepName)
                );
            }
            $this->formBuilder = $this->formFactory->createBuilder(
                new NavigatorType(),
                $data, // TODO: $this->getCurrentStepData()
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
     * Returns the chosen path.
     *
     * @return PathInterface|null
     */
    private function getChosenPath()
    {
        if ($this->getForm()->isValid()) {

            if ($this->getForm()->has('_data')) {
                $this->getFlow()->getData()->setStepData(
                    $this->getFlow()->getCurrentStep(),
                    $this->getForm()->get('_data')->getData()
                );
            }

            foreach ($this->getAvailablePaths() as $i => $path) {
                if ($this->getForm()->get(sprintf('_path#%d', $i))->isClicked()) {
                    $this->getFlow()->getHistory()->addTakenPath($path->getSource(), $i);

                    return $path;
                }
            }

            throw new \LogicException(sprintf(
                'The chosen path seem to disapear magically'
            ));
        }

        return null;
    }

    /**
     * Retrieve the destination.
     *
     * @return StepInterface|null The destination step to reached.
     */
    private function retrieveDestination()
    {
        if ($this->getForm()->has('_back') && $this->getForm()->get('_back')->isClicked()) {
            $previousStep = $this
                ->getMap()
                ->getStep($this->getFlow()->getPreviousStep())
            ;
            $this->getFlow()->getHistory()->retraceTakenPath($previousStep);

            return $previousStep;
        }

        $path = $this->getChosenPath();

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
                $this->save();
            }
            $this->resetForm();
        }
    }
}