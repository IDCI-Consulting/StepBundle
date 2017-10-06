<?php

namespace IDCI\Bundle\StepBundle\Controller;

use IDCI\Bundle\StepBundle\Path\Event\Configuration\PathEventActionConfigurationRegistryInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Api PathEventActionConfiguration Controller.
 */
class ApiPathEventActionConfigurationController extends FOSRestController
{
    /**
     * [GET] /path-event-actions-configurations.
     *
     * Retrieve path event actions configurations
     *
     * @Get("/path-event-actions-configurations.{_format}")
     *
     * @param string $_format
     *
     * @return Response
     */
    public function getPathEventActionConfigurationsAction($_format)
    {
        $view = View::create()->setFormat($_format);
        $configurations = $this->get(PathEventActionConfigurationRegistryInterface::class)->getConfigurations();

        $configurations = array_filter($configurations, function ($configuration) {
            return !$configuration->isAbstract();
        });

        $view->setData(array_values($configurations));

        return $this->handleView($view);
    }
}
