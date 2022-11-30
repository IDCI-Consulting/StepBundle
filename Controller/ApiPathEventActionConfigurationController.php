<?php

namespace IDCI\Bundle\StepBundle\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use IDCI\Bundle\StepBundle\Path\Event\Configuration\PathEventActionConfigurationRegistryInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Api PathEventActionConfiguration Controller.
 */
class ApiPathEventActionConfigurationController extends AbstractFOSRestController
{
    /**
     * [GET] /path-event-actions-configurations.
     *
     * Retrieve path event actions configurations
     *
     * @Get("/path-event-actions-configurations.{_format}")
     */
    public function getPathEventActionConfigurationsAction(string $_format, PathEventActionConfigurationRegistryInterface $registry): Response
    {
        $view = View::create()->setFormat($_format);
        $configurations = $registry->getConfigurations();

        $configurations = array_filter($configurations, function ($configuration) {
            return !$configuration->isAbstract();
        });

        $view->setData(array_values($configurations));

        return $this->handleView($view);
    }
}
