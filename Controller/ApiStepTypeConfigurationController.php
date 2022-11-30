<?php

namespace IDCI\Bundle\StepBundle\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use IDCI\Bundle\StepBundle\Step\Type\Configuration\StepTypeConfigurationRegistryInterface;
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
     */
    public function getStepTypesConfigurationsAction(string $_format, StepTypeConfigurationRegistryInterface $registry): Response
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
