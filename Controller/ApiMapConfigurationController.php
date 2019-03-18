<?php

namespace IDCI\Bundle\StepBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

/**
 * Api MapConfiguration Controller.
 */
class ApiMapConfigurationController extends FOSRestController
{
    /**
     * [GET] /map-configuration.
     *
     * Retrieve the map configuration
     *
     * @Get("/map-configuration.{_format}")
     *
     * @param string $_format
     *
     * @return Response
     */
    public function getMapConfigurationAction($_format)
    {
        $view = View::create()->setFormat($_format);
        $configuration = Yaml::parseFile(sprintf('%s/../Resources/config/map_options.yml', __DIR__));
        $view->setData($configuration);

        return $this->handleView($view);
    }
}
