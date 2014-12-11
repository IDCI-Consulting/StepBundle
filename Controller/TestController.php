<?php

namespace IDCI\Bundle\StepBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Test controller.
 *
 * @Route("/step")
 */
class TestController extends Controller
{
    /**
     * Test.
     *
     * @Route("/", name="idci_step")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $map = $this
            ->get('idci_step.map.factory')
            ->createBuilder()
            ->addStep('intro', 'content', array(
                'name'        => 'Introduction',
                'description' => 'The first step',
                'content'     => '<h1>My content</h1>',
            ))
            ->addStep('personal', 'form', array(
                'name'        => 'Personal information',
                'description' => 'The personal data step',
            ))
            ->addStep('purchase', 'form', array(
                'name'        => 'Purchase information',
                'description' => 'The purchase data step',
            ))
            ->addStep('fork1', 'form', array(
                'name'        => 'Fork1 information',
                'description' => 'The fork1 data step',
            ))
            ->addStep('fork2', 'form', array(
                'name'        => 'Fork2 information',
                'description' => 'The fork2 data step',
            ))
            ->addStep('end', 'content', array(
                'name'        => 'The end',
                'description' => 'The last data step',
                'content'     => '<h1>The end</h1>',
            ))
            ->addPath(
                'intro',
                'single',
                array(
                    'label'         => 'next',
                    'destination'   => 'personal'
                )
            )
            ->addPath(
                'personal',
                'multiple',
                array(
                    'label'         => 'next',
                    'destinations'  => array(
                        'purchase'  => array(
                            'rules' => array()
                        ),
                        'fork2'     => array(
                            'rules' => array()
                        )
                    )
                )
            )
            ->addPath(
                'purchase',
                'single',
                array(
                    'label'         => 'next',
                    'destination'   => 'fork1'
                )
            )
            ->addPath(
                'purchase',
                'single',
                array(
                    'label'         => 'next',
                    'destination'   => 'fork2',
                )
            )
            ->addPath(
                'fork1',
                'single',
                array(
                    'label'         => 'next',
                    'destination'   => 'end'
                )
            )
            ->addPath(
                'fork2',
                'single',
                array(
                    'label'         => 'next',
                    'destination'   => 'end'
                )
            )
            ->addPath(
                'end',
                'end',
                array(
                    'label'           => 'Fin',
                    'storageProvider' => 'step.storage.provider.participation',
                )
            )
        ;

        var_dump($map);die;
    }
}