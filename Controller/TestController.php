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
        /*
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
                    'label' => 'Back to first step',
                ),
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('first_name', 'text', array(
                        'constraints' => array(
                            new \Symfony\Component\Validator\Constraints\NotBlank()
                        )
                    ))
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
            ->createNavigator(
                $request,
                $map,
                array(),
                json_decode('{
                    "personal":{"first_name":"John","last_name":"DOE"},
                    "purchase":{"item":"Something","purchase_date":{"date":{"month":"10","day":"10","year":"2010"},"time":{"hour":"10","minute":"10"}}}
                }', true)
            )
        ;
        */

        $navigator = $this
            ->get('idci_step.navigator.factory')
            ->createNavigator(
                $request,
                'participation_map'
            )
        ;


        if ($navigator->hasFinished()) {
            $navigator->clear();
        }

        if ($navigator->hasNavigated()) {
            return $this->redirect($this->generateUrl('idci_step'));
        }

        return array('navigator' => $navigator);
    }
}
