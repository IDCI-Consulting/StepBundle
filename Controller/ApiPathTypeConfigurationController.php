<?php

namespace IDCI\Bundle\StepBundle\Controller;

use IDCI\Bundle\StepBundle\Path\Type\Configuration\PathTypeConfigurationRegistryInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Api PathTypeConfiguration Controller.
 */
class ApiPathTypeConfigurationController extends AbstractFOSRestController
{
    /**
     * [GET] /path-types-configurations.
     *
     * Retrieve path types configurations
     *
     * @Get("/path-types-configurations.{_format}")
     *
     * @param string $_format
     *
     * @return Response
     */
    public function getPathTypesConfigurationsAction(
        $_format,
        PathTypeConfigurationRegistryInterface $registry
    ) {
        $view = View::create()->setFormat($_format);
        $configurations = $registry->getConfigurations();

        $configurations = array_filter($configurations, function ($configuration) {
            return !$configuration->isAbstract();
        });

        $view->setData(array_values($configurations));

        return $this->handleView($view);
    }
}
