<?php

namespace IDCI\Bundle\StepBundle\Controller;

use IDCI\Bundle\StepBundle\Step\Event\Configuration\StepEventActionConfigurationRegistryInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Api StepEventActionConfiguration Controller.
 */
class ApiStepEventActionConfigurationController extends AbstractFOSRestController
{
    /**
     * [GET] /step-event-actions-configurations.
     *
     * Retrieve step event actions configurations
     *
     * @Get("/step-event-actions-configurations.{_format}")
     *
     * @param string $_format
     *
     * @return Response
     */
    public function getStepEventActionConfigurationsAction(
        $_format,
        StepEventActionConfigurationRegistryInterface $registry
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
