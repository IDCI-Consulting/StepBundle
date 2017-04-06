<?php

namespace IDCI\Bundle\StepBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Annotations;
use Symfony\Component\HttpFoundation\Response;

/**
 * Api PathTypeConfiguration Controller
 */
class ApiPathTypeConfigurationController extends FOSRestController
{
    /**
     * [GET] /path-types-configurations
     *
     * Retrieve path types configurations
     *
     * @Get("/path-types-configurations.{_format}")
     *
     * @param string $_format
     *
     * @return Response
     */
    public function getPathTypesConfigurationsAction($_format)
    {
        $view = View::create()->setFormat($_format);
        $configurations = $this->get('idci_step.path_type_configuration.registry')->getConfigurations();

        $configurations = array_filter($configurations, function ($configuration) {
            return !$configuration->isAbstract();
        });

        $view->setData(array_values($configurations));

        return $this->handleView($view);
    }
}
