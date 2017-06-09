<?php

namespace IDCI\Bundle\StepBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Annotations;
use Symfony\Component\HttpFoundation\Response;

/**
 * Api StepEventActionConfiguration Controller
 */
class ApiStepEventActionConfigurationController extends FOSRestController
{
    /**
     * [GET] /step-event-actions-configurations
     *
     * Retrieve step event actions configurations
     *
     * @Get("/step-event-actions-configurations.{_format}")
     *
     * @param string $_format
     *
     * @return Response
     */
    public function getStepEventActionConfigurationsAction($_format)
    {
        $view = View::create()->setFormat($_format);
        $configurations = $this->get('idci_step.step_event_action_configuration.registry')->getConfigurations();

        $configurations = array_filter($configurations, function ($configuration) {
            return !$configuration->isAbstract();
        });

        $view->setData(array_values($configurations));

        return $this->handleView($view);
    }
}