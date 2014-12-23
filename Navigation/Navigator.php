<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\DataStore\DataStoreInterface;
use IDCI\Bundle\StepBundle\Flow\Flow;

class Navigator extends AbstractNavigator
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var DataStoreInterface
     */
    private $dataStore;

    /**
     * @var MapInterface
     */
    private $map;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var FlowInterface
     */
    private $flow;

    /**
     * Constructor
     *
     * @param FormFactoryInterface  $formFactory    The form factory.
     * @param DataStoreInterface    $dataStore      The data store using to keep the flow.
     * @param MapInterface          $map            The map to navigate.
     * @param Request               $request        The HTTP request.
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        DataStoreInterface   $dataStore,
        MapInterface         $map,
        Request              $request
    )
    {
        $this->formFactory = $formFactory;
        $this->dataStore   = $dataStore;
        $this->map         = $map;
        $this->request     = $request;

        $this->initFlow();
    }

    /**
     * Init flow
     *
     * @TODO
     */
    private function initFlow()
    {
        $this->flow = new Flow();

        if ($this->request->isMethod('POST')) {
            var_dump($this->request->request->get(self::getName()));
        }
    }

    /**
     * Returns the navigation form builder
     *
     * @return Symfony\Component\Form\FormBuilderInterface
     */
    private function getFormBuilder()
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
    private function getForm()
    {
        return $this->getFormBuilder()->getForm();
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
        return $this->flow;
    }

    /**
     * {@inheritdoc}
     */
    public function createStepView()
    {
        return $this->getForm()->createView();
    }

    /**
     * {@inheritdoc}
     */
    public function getPositionName()
    {
        return sprintf('%s_%s',
            $this->getMap()->getName(),
            $this->getCurrentStep()->getName()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentStep()
    {
        return $this->getMap()->getStep($this->getFlow()->getCurrentStep());
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailablePaths()
    {
        return $this->getMap()->getPaths($this->getFlow()->getCurrentStep());
    }
}