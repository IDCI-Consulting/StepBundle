<?php

namespace IDCI\Bundle\StepBundle\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use IDCI\Bundle\StepBundle\Step\Event\Configuration\StepEventActionConfigurationRegistryInterface;
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
     */
    public function getStepEventActionConfigurationsAction(string $_format, StepEventActionConfigurationRegistryInterface $registry): Response
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
