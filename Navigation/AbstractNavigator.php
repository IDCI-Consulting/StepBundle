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

abstract class AbstractNavigator implements NavigatorInterface 
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var DataStoreInterface
     */
    protected $dataStore;

    /**
     * @var MapInterface
     */
    protected $map;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var NavigationLoggerInterface
     */
    protected $logger;

    /**
     * @var FlowInterface
     */
    protected $flow;

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
        $this->formFactory = $formFactory;
        $this->dataStore   = $dataStore;
        $this->map         = $map;
        $this->request     = $request;
        $this->logger      = $logger;

        if ($logger) {
            $this->logger->startInit();
        }

        $this->initFlow();

        if ($logger) {
            $this->logger->stopInit($this);
        }
    }

    /**
     * Returns the navigation form
     *
     * @return Symfony\Component\Form\FormInterface
     */
    abstract protected function getForm();

    /**
     * Init the flow
     */
    abstract protected function initFlow();

    /**
     * Returns the navigator name
     *
     * @return string
     */
    public static function getName()
    {
        return 'idci_step_navigator';
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