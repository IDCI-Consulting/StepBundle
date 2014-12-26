<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use IDCI\Bundle\StepBundle\Flow\Flow;

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
     * Init the flow.
     */
    protected function initFlow()
    {
        $this->flow = $this->retrieveFlow();

        if ($this->request->isMethod('POST')) {
            $this->getForm()->handleRequest($this->request);
            if ($this->getForm()->isValid()) {
                $path = $this->getChoosenPath();
                $destination = $path->resolveDestination($this);

                var_dump($destination);die;
            }

            //var_dump($this->request->request->get(self::getName()));
        }
    }

    /**
     * Retrieve the flow.
     *
     * @return FlowInterface
     */
    protected function retrieveFlow()
    {
        $flow = new Flow();
        $flowRaw = $this->dataStore->get($this->map->getName());

        if (empty($flowRaw)) {
            $flow->setCurrentStep($this->map->getFirstStepName());

            return $flow;
        }

        var_dump($flowRaw);die;
    }

    /**
     * Returns the choosen path.
     *
     * @return PathInterface
     */
    public function getChoosenPath()
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
}