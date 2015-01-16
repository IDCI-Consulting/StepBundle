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
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $map = $this
            ->get('idci_step.map.builder.factory')
            ->createNamedBuilder('test map')
            ->addStep('intro', 'html', array(
                'title'       => 'Introduction',
                'description' => 'The first step',
                'content'     => '<h1>My content</h1>',
            ))
            ->addStep('personal', 'form', array(
                'title'            => 'Personal information',
                'description'      => 'The personal data step',
                'previous_options' => array(
                    'label' => 'Retour au début',
                ),
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('first_name', 'text')
                    ->add('last_name', 'text')
                ,
            ))
            ->addStep('purchase', 'form', array(
                'title'       => 'Purchase information',
                'description' => 'The purchase data step',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('item', 'text')
                    ->add('purchase_date', 'datetime')
                ,
            ))
            ->addStep('fork1', 'form', array(
                'title'       => 'Fork1 information',
                'description' => 'The fork1 data step',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('fork1_data', 'textarea')
                ,
            ))
            ->addStep('fork2', 'form', array(
                'title'       => 'Fork2 information',
                'description' => 'The fork2 data step',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('fork2_data', 'textarea')
                ,
            ))
            ->addStep('end', 'html', array(
                'title'       => 'The end',
                'description' => 'The last data step',
                'content'     => '<h1>The end</h1>',
            ))
            ->addPath(
                'single',
                array(
                    'source'       => 'intro',
                    'destination'  => 'personal',
                    'next_options' => array(
                        'label' => 'next',
                    ),
                )
            )
            ->addPath(
                'conditional_destination',
                array(
                    'source'        => 'personal',
                    'destinations'  => array(
                        'purchase'  => array(
                            'rules' => array()
                        ),
                        'fork2'     => array(
                            'rules' => array()
                        ),
                    )
                )
            )
            ->addPath(
                'single',
                array(
                    'source'      => 'purchase',
                    'destination' => 'fork1'
                )
            )
            ->addPath(
                'single',
                array(
                    'source'       => 'purchase',
                    'destination'  => 'fork2',
                    'next_options' => array(
                        'label' => 'next p',
                    ),
                )
            )
            ->addPath(
                'single',
                array(
                    'source'       => 'fork1',
                    'destination'  => 'end',
                    'next_options' => array(
                        'label' => 'next f',
                    ),
                )
            )
            ->addPath(
                'single',
                array(
                    'source'       => 'fork2',
                    'destination'  => 'end',
                    'next_options' => array(
                        'label' => 'last',
                    ),
                )
            )
            ->addPath(
                'end',
                array(
                    'source'           => 'end',
                    'next_options'     => array(
                        'label' => 'end',
                    ),
                )
            )
            ->getMap()
        ;

        $navigator = $this
            ->get('idci_step.navigator.factory')
            ->createNavigator($map, $request)
        ;

        if ($navigator->hasNavigated()) {
            return $this->redirect($this->generateUrl('idci_step'));
        }

        if ($navigator->hasFinished()) {
            $navigator->clear();

            return $this->redirect($this->generateUrl('idci_step'));
        }

        return array('navigator' => $navigator);
    }
}