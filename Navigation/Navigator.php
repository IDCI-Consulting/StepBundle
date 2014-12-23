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
     * Returns the navigation form builder
     *
     * @return Symfony\Component\Form\FormBuilderInterface
     */
    protected function getFormBuilder()
    {
        return $this->formFactory->createBuilder(
            new NavigatorType(),
            null, 
            array('navigator' => $this)
        );
    }

    /**
     * Returns the navigation form
     *
     * @return Symfony\Component\Form\FormInterface
     */
    protected function getForm()
    {
        return $this->getFormBuilder()->getForm();
    }

    /**
     * Init flow
     *
     * @TODO
     */
    protected function initFlow()
    {
        $this->flow = new Flow();

        if ($this->request->isMethod('POST')) {
            var_dump($this->request->request->get(self::getName()));
        }
    }
}