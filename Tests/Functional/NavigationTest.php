<?php

namespace IDCI\Bundle\StepBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class NavigationTest extends \PHPUnit_Framework_TestCase
{
    private static $container;

    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/../AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        self::$container = $kernel->getContainer();
    }

    public function setUp()
    {
        $this->map = self::$container
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
                'builder' => self::$container->get('form.factory')->createBuilder()
                    ->add('first_name', 'text')
                    ->add('last_name', 'text')
                ,
            ))
            ->addStep('purchase', 'form', array(
                'title'       => 'Purchase information',
                'description' => 'The purchase data step',
                'builder' => self::$container->get('form.factory')->createBuilder()
                    ->add('item', 'text')
                    ->add('purchase_date', 'datetime')
                ,
            ))
            ->addStep('fork1', 'form', array(
                'title'       => 'Fork1 information',
                'description' => 'The fork1 data step',
                'builder' => self::$container->get('form.factory')->createBuilder()
                    ->add('fork1_data', 'textarea')
                ,
            ))
            ->addStep('fork2', 'form', array(
                'title'       => 'Fork2 information',
                'description' => 'The fork2 data step',
                'builder' => self::$container->get('form.factory')->createBuilder()
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
    }

    public function getData()
    {
        $data = array();

        # 0
        $data[] = array(
            'GET',
            null,
            'intro',
            0,
            array(),
            array()
        );

        # 1
        $data[] = array(
            'POST',
            'intro',
            'personal',
            0,
            array(),
            array()
        );

        # 2
        $data[] = array(
            'POST',
            'personal',
            'purchase',
            0,
            array(
                'first_name' => 'John',
                'last_name' => 'Doe'
            ),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                )
            )
        );

        # 3
        $date = \DateTime::createFromFormat('Y-m-d\TH:i:s', '2015-01-01T00:00:00');
        $data[] = array(
            'POST',
            'purchase',
            'fork1',
            0,
            array(
                'item' => 'foo',
                'purchase_date' => array(
                    'date' => array(
                        'year' => '2015',
                        'month' => '1',
                        'day' => '1'
                    ),
                    'time' => array(
                        'hour' => '0',
                        'minute' => '0'
                    )
                )
            ),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                ),
                'purchase' => array(
                    'item' => 'foo',
                    'purchase_date' => $date
                )
            )
        );

        # 4
        $data[] = array(
            'POST',
            'fork1',
            'end',
            0,
            array(
                'fork1_data' => 'foo'
            ),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                ),
                'purchase' => array(
                    'item' => 'foo',
                    'purchase_date' => $date->format(\DateTime::ISO8601) // TO CORRECT (should be a \DateTime)
                ),
                'fork1' => array(
                    'fork1_data' => 'foo'
                )
            )
        );

        # 5
        $data[] = array(
            'POST',
            'purchase',
            'fork1',
            false,
            array(),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                ),
                'purchase' => array(
                    'item' => 'foo',
                    'purchase_date' => $date->format(\DateTime::ISO8601) // TO CORRECT (should be a \DateTime)
                ),
                'fork1' => array(
                    'fork1_data' => 'foo'
                )
            )
        );

        # 6
        $data[] = array(
            'POST',
            'personal',
            'purchase',
            false,
            array(),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                ),
                'purchase' => array(
                    'item' => 'foo',
                    'purchase_date' => $date->format(\DateTime::ISO8601) // TO CORRECT (should be a \DateTime)
                ),
                'fork1' => array( // TO CORRECT (TO REMOVE?)
                    'fork1_data' => 'foo'
                )
            )
        );

        # 7
        $date = \DateTime::createFromFormat('Y-m-d\TH:i:s', '2016-01-01T00:00:00');
        $data[] = array(
            'POST',
            'purchase',
            'fork2',
            1,
            array(
                'item' => 'foo',
                'purchase_date' => array(
                    'date' => array(
                        'year' => '2016',
                        'month' => '1',
                        'day' => '1'
                    ),
                    'time' => array(
                        'hour' => '0',
                        'minute' => '0'
                    )
                )
            ),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                ),
                'purchase' => array(
                    'item' => 'foo',
                    'purchase_date' => $date
                ),
                'fork1' => array( // TO CORRECT (TO REMOVE?)
                    'fork1_data' => 'foo'
                )
            )
        );

        # 8
        $data[] = array(
            'POST',
            'fork2',
            'end',
            1,
            array(
                'fork2_data' => 'bar'
            ),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                ),
                'purchase' => array(
                    'item' => 'foo',
                    'purchase_date' => $date->format(\DateTime::ISO8601) // TO CORRECT (should be a \DateTime)
                ),
                'fork1' => array( // TO CORRECT (TO REMOVE?)
                    'fork1_data' => 'foo'
                ),
                'fork2' => array(
                    'fork2_data' => 'bar'
                )
            )
        );

        return $data;
    }

    /**
     * @dataProvider getData
     */
    public function testNavigate(
        $method,
        $previousStep,
        $currentStep,
        $destination,
        array $arguments,
        array $expectedData
    )
    {
        $request = new Request();
        $request->setMethod($method);
        $map = $this->map;

        $requestParameters = array(
            '_map_name' => $map->getName(),
            '_map_finger_print' => $map->getFingerPrint(),
            '_current_step' => $currentStep
        );

        if (false === $destination) {
            $requestParameters['_back'] = true;
        } else {
            $requestParameters[sprintf('_path#%d', $destination)] = true;
        }

        if (!empty($arguments)) {
            $requestParameters['_data'] = $arguments;
        }

        $request->request->set('idci_step_navigator', $requestParameters);

        $navigator = self::$container
            ->get('idci_step.navigator.factory')
            ->createNavigator($map, $request)
        ;

        $flow = $navigator->getFlow();
        $history = $flow->getHistory();
        $data = $flow->getData();

        $this->assertEquals($previousStep, $flow->getPreviousStep());
        $this->assertEquals($currentStep, $flow->getCurrentStep());

        foreach ($expectedData as $step => $value) {
            $this->assertEquals($value, $data->getStepData($step));
        }
    }
}