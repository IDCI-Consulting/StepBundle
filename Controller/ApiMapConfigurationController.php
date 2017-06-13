<?php

namespace IDCI\Bundle\StepBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Annotations;
use Symfony\Component\HttpFoundation\Response;

/**
 * Api MapConfiguration Controller
 */
class ApiMapConfigurationController extends FOSRestController
{
    /**
     * [GET] /map-configuration
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
        $configuration = array(
            'extra_form_options' => array(
                'display_step_in_url' => array(
                    'extra_form_type' => 'checkbox',
                    'options' => array(
                        'required' => false,
                        'data'     => false
                    )
                ),
                'final_destination' => array(
                    'extra_form_type' => 'text',
                    'options' => array(
                        'required' => false
                    )
                ),
                'reset_flow_data_on_init' => array(
                    'extra_form_type' => 'checkbox',
                    'options' => array(
                        'required' => false,
                        'data'     => false
                    )
                ),
                'form_action' => array(
                    'extra_form_type' => 'text',
                    'options' => array(
                        'required' => false
                    )
                ),
                'first_step_name' => array(
                    'extra_form_type' => 'text',
                    'options' => array(
                        'required' => false
                    )
                ),
            )
        );

        $view->setData($configuration);

        return $this->handleView($view);
    }
}
