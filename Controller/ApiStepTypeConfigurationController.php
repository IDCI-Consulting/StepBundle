<?php

namespace IDCI\Bundle\StepBundle\Controller;

use IDCI\Bundle\StepBundle\Step\Type\Configuration\StepTypeConfigurationRegistryInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Api StepTypeConfiguration Controller.
 */
class ApiStepTypeConfigurationController extends AbstractFOSRestController
{
    /**
     * [GET] /step-types-configurations.
     *
     * Retrieve step types configurations
     *
     * @Get("/step-types-configurations.{_format}")
     *
     * @param string $_format
     *
     * @return Response
     */
    public function getStepTypesConfigurationsAction(
        $_format,
        StepTypeConfigurationRegistryInterface $registry
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
