<?php

namespace IDCI\Bundle\StepBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Annotations;
use Symfony\Component\HttpFoundation\Response;

/**
 * Api StepTypeConfiguration Controller
 */
class ApiStepTypeConfigurationController extends FOSRestController
{
    /**
     * [GET] /step-types-configurations
     *
     * Retrieve step types
     *
     * @Get("/step-types-configurations.{_format}")
     *
     * @param string $_format
     *
     * @return Response
     */
    public function getStepTypesConfigurationsAction($_format)
    {
        $view = View::create()->setFormat($_format);
        $configurations = $this->get('idci_step.step_type_configuration.registry')->getConfigurations();

        $configurations = array_filter($configurations, function ($configuration) {
            return !$configuration->isAbstract();
        });

        $view->setData(array_values($configurations));

        return $this->handleView($view);
    }
}
