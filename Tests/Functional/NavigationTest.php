<?php

namespace IDCI\Bundle\StepBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class NavigationTest extends \PHPUnit_Framework_TestCase
{
    private static $request;
    private static $map;
    private static $flow;
    private static $navigator;

    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/../AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        self::$request = new Request();
        self::$request->setMethod('POST');
        $container = $kernel->getContainer();

        self::$map = $container
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
                'builder' => $container->get('form.factory')->createBuilder()
                    ->add('first_name', 'text')
                    ->add('last_name', 'text')
                ,
            ))
            ->addStep('purchase', 'form', array(
                'title'       => 'Purchase information',
                'description' => 'The purchase data step',
                'builder' => $container->get('form.factory')->createBuilder()
                    ->add('item', 'text')
                    ->add('purchase_date', 'datetime')
                ,
            ))
            ->addStep('fork1', 'form', array(
                'title'       => 'Fork1 information',
                'description' => 'The fork1 data step',
                'builder' => $container->get('form.factory')->createBuilder()
                    ->add('fork1_data', 'textarea')
                ,
            ))
            ->addStep('fork2', 'form', array(
                'title'       => 'Fork2 information',
                'description' => 'The fork2 data step',
                'builder' => $container->get('form.factory')->createBuilder()
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
                    'storage_provider' => 'step.storage.provider.participation',
                    'next_options'     => array(
                        'label' => 'Fin',
                    ),
                )
            )
            ->getMap()
        ;

        self::$navigator = $container
            ->get('idci_step.navigator.factory')
            ->createNavigator(self::$map, self::$request)
        ;

        self::$flow = self::$navigator->getFlow();
    }

    public function getData()
    {
        $data = array();

        # 0
        $data[] = array(
            null,
            'intro',
            array('first_name')
        );

        # 1
        $data[] = array(
            'intro',
            'personal',
            array(
                'first_name' => 'John',
                'last_name' => 'Doe'
            )
        );

        return $data;
    }

    /**
     * @dataProvider getData
     */
    public function testNavigate($previousStep, $currentStep, array $arguments = array())
    {
        $request = self::$request;
        $flow = self::$flow;
        $map = self::$map;
        $history = $flow->getHistory();
        $data = $flow->getData();

        $arguments = array_merge(
            array(
                '_map_name' => 'test map',
                '_map_finger_print' => '123abc',
                '_current_step' => $previousStep,
                '_previous_step' => $currentStep,
                '_path0' => true
            ),
            $arguments
        );
        $request->request->set('idci_step_navigator', $arguments);

        var_dump('---', $flow->getPreviousStep(), $flow->getCurrentStep(), $previousStep, $currentStep);
        self::$navigator->navigate();
        var_dump('...', $flow->getPreviousStep(), $flow->getCurrentStep(), $previousStep, $currentStep);

        $this->assertEquals($previousStep, $flow->getPreviousStep());
        $this->assertEquals($currentStep, $flow->getCurrentStep());
    }
}