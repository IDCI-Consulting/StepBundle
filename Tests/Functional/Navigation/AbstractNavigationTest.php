<?php

namespace IDCI\Bundle\StepBundle\Tests\Functional\Navigation;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
abstract class AbstractNavigationTest extends \PHPUnit_Framework_TestCase
{
    protected static $container;

    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/../../AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        self::$container = $kernel->getContainer();
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
            array(),
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
            array(),
            array(),
            array(
                'first_name' => null,
                'last_name' => null
            )
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
            ),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                )
            ),
            array(
                'item' => null,
                'purchase_date' => null
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
                )
            ),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                ),
                'purchase' => array(
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
                )
            ),
            array(
                'fork1_data' => null
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
                'fork1' => array(
                    'fork1_data' => 'foo'
                )
            ),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                ),
                'purchase' => array(
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
                'fork1' => array(
                    'fork1_data' => 'foo'
                )
            ),
            array()
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
                )
            ),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                ),
                'purchase' => array(
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
                'fork1' => array(
                    'fork1_data' => 'foo'
                )
            ),
            array(
                'fork1_data' => 'foo'
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
                )
            ),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                ),
                'purchase' => array(
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
                'fork1' => array(
                    'fork1_data' => 'foo'
                )
            ),
            array(
                'item' => 'foo',
                'purchase_date' => $date
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
                )
            ),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                ),
                'purchase' => array(
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
                'fork1' => array(
                    'fork1_data' => 'foo'
                )
            ),
            array(
                'fork2_data' => null
            )
        );

        # 8
        $data[] = array(
            'POST',
            'fork2',
            'end',
            0,
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
                'fork2' => array(
                    'fork2_data' => 'bar'
                )
            ),
            array(
                'personal' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe'
                ),
                'purchase' => array(
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
                'fork1' => array(
                    'fork1_data' => 'foo'
                ),
                'fork2' => array(
                    'fork2_data' => 'bar'
                )
            ),
            array()
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
        array $expectedRawData,
        array $expectedRemindedData,
        array $expectedData
    )
    {
        $request = new Request();
        $request->setMethod($method);
        $request->setSession(self::$container->get('session'));
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
        $data = $flow->getData()->getAll();
        $remindedData = $flow->getData();

        $this->assertEquals($previousStep, $flow->getPreviousStepName());
        $this->assertEquals($currentStep, $flow->getCurrentStepName());

        $this->assertEquals($expectedRawData, $data['data']);
        $this->assertEquals($expectedRemindedData, $data['remindedData']);

        $normalizedData = $navigator->getCurrentNormalizedStepData();

        $this->assertEquals($expectedData, $normalizedData);
    }
}