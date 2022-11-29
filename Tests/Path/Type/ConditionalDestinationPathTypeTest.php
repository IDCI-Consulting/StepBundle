<?php

namespace IDCI\Bundle\StepBundle\Tests\Path\Type;

use IDCI\Bundle\StepBundle\ConditionalRule\ConditionalRuleRegistryInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\Type\ConditionalDestinationPathType;
use IDCI\Bundle\StepBundle\Step\Step;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class ConditionalDestinationPathTypeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $tokenStorage = $this
            ->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getToken', 'setToken', 'isGranted'))
            ->getMock()
        ;
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null))
        ;

        $twigStringLoader = new \Twig_Loader_Array();
        $twigEnvironment = new Environment($twigStringLoader, array());

        $this->pathType = new ConditionalDestinationPathType(
            $twigEnvironment,
            $tokenStorage,
            $this->createMock(SessionInterface::class),
            $this->createMock(ConditionalRuleRegistryInterface::class)
        );
    }

    public function testResolveDestination()
    {
        $flow = $this->createMock(FlowInterface::class);
        $flow
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue(array(
                'data' => array(
                    'step1' => array(
                        'firstname' => 'john',
                        'lastname' => 'doe',
                    ),
                ),
            )))
        ;

        $navigator = $this->createMock(NavigatorInterface::class);
        $navigator
            ->expects($this->any())
            ->method('getFlow')
            ->will($this->returnValue($flow))
        ;

        $this->assertEquals(
            'destination_1',
            $this->pathType->resolveDestination(
                array(
                    'source' => 'step1',
                    'default_destination' => 'default',
                    'destinations' => array(
                        'destination_1' => true,
                        'destination_2' => true,
                    ),
                ),
                $navigator
            )
        );

        $this->assertEquals(
            'destination_2',
            $this->pathType->resolveDestination(
                array(
                    'source' => 'step1',
                    'default_destination' => 'default',
                    'destinations' => array(
                        'destination_1' => false,
                        'destination_2' => true,
                    ),
                ),
                $navigator
            )
        );

        $this->assertEquals(
            'default',
            $this->pathType->resolveDestination(
                array(
                    'source' => 'step1',
                    'default_destination' => 'default',
                    'destinations' => array(
                        'destination_1' => false,
                        'destination_2' => false,
                    ),
                ),
                $navigator
            )
        );

        $this->assertEquals(
            'destination_2',
            $this->pathType->resolveDestination(
                array(
                    'source' => 'step1',
                    'default_destination' => 'default',
                    'destinations' => array(
                        'destination_1' => '{{ flow_data.data.step1.firstname == "dummy" }}',
                        'destination_2' => '{{ flow_data.data.step1.firstname == "john" }}',
                    ),
                ),
                $navigator
            )
        );

        $this->assertEquals(
            null,
            $this->pathType->resolveDestination(
                array(
                    'source' => 'step1',
                    'default_destination' => 'default',
                    'stop_navigation' => true,
                    'destinations' => array(
                        'destination_1' => true,
                        'destination_2' => true,
                    ),
                ),
                $navigator
            )
        );

        $this->assertEquals(
            null,
            $this->pathType->resolveDestination(
                array(
                    'source' => 'step1',
                    'default_destination' => 'default',
                    'stop_navigation' => '{{ flow_data.data.step1.firstname == "john" }}',
                    'destinations' => array(
                        'destination_1' => true,
                        'destination_2' => true,
                    ),
                ),
                $navigator
            )
        );
    }

    public function testbuildPath()
    {
        $step1 = new Step(array('name' => 'step1'));
        $step2 = new Step(array('name' => 'step2'));
        $step3 = new Step(array('name' => 'step3'));
        $stepEnd = new Step(array('name' => 'stepEnd'));

        $path = $this->pathType->buildPath(
            array(
                'step1' => $step1,
                'step2' => $step2,
                'step3' => $step3,
                'stepEnd' => $stepEnd,
            ),
            array(
                'source' => 'step1',
                'default_destination' => 'stepEnd',
                'destinations' => array(
                    'step2' => false,
                    'step3' => false,
                ),
            )
        );

        $this->assertEquals($path->getSource(), $step1);
        $this->assertEquals(
            $path->getDestinations(),
            array(
                'step2' => $step2,
                'step3' => $step3,
                'stepEnd' => $stepEnd,
            )
        );

        $flow = $this->createMock(FlowInterface::class);
        $flow
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue(null))
        ;
        $navigator = $this->createMock(NavigatorInterface::class);
        $navigator
            ->expects($this->any())
            ->method('getFlow')
            ->will($this->returnValue($flow))
        ;
        $this->assertEquals($path->resolveDestination($navigator), $stepEnd);
    }
}
