<?php

namespace IDCI\Bundle\StepBundle\Tests\Path\Type;

use IDCI\Bundle\StepBundle\ConditionalRule\ConditionalRuleRegistryInterface;
use IDCI\Bundle\StepBundle\Flow\FlowDataInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\Type\ConditionalDestinationPathType;
use IDCI\Bundle\StepBundle\Step\Step;
use IDCI\Bundle\StepBundle\Step\Type\HtmlStepType;
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
            ->setMethods(['getToken', 'setToken', 'isGranted'])
            ->getMock()
        ;
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null))
        ;

        $twigStringLoader = new \Twig_Loader_Array();
        $twigEnvironment = new Environment($twigStringLoader, []);

        $this->pathType = new ConditionalDestinationPathType(
            $twigEnvironment,
            $tokenStorage,
            $this->createMock(SessionInterface::class),
            $this->createMock(ConditionalRuleRegistryInterface::class)
        );
    }

    public function testResolveDestination()
    {
        $flowData = $this->createMock(FlowDataInterface::class);
        $flowData
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue([
                'step1' => [
                    'firstname' => 'john',
                    'lastname' => 'doe',
                ],
            ]))
        ;

        $flow = $this->createMock(FlowInterface::class);
        $flow
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue($flowData))
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
                [
                    'source' => 'step1',
                    'default_destination' => 'default',
                    'destinations' => [
                        'destination_1' => true,
                        'destination_2' => true,
                    ],
                ],
                $navigator
            )
        );

        $this->assertEquals(
            'destination_2',
            $this->pathType->resolveDestination(
                [
                    'source' => 'step1',
                    'default_destination' => 'default',
                    'destinations' => [
                        'destination_1' => false,
                        'destination_2' => true,
                    ],
                ],
                $navigator
            )
        );

        $this->assertEquals(
            'default',
            $this->pathType->resolveDestination(
                [
                    'source' => 'step1',
                    'default_destination' => 'default',
                    'destinations' => [
                        'destination_1' => false,
                        'destination_2' => false,
                    ],
                ],
                $navigator
            )
        );

        $this->assertEquals(
            'destination_2',
            $this->pathType->resolveDestination(
                [
                    'source' => 'step1',
                    'default_destination' => 'default',
                    'destinations' => [
                        'destination_1' => '{{ flow_data.data.step1.firstname == "dummy" }}',
                        'destination_2' => '{{ flow_data.data.step1.firstname == "john" }}',
                    ],
                ],
                $navigator
            )
        );

        $this->assertEquals(
            null,
            $this->pathType->resolveDestination(
                [
                    'source' => 'step1',
                    'default_destination' => 'default',
                    'stop_navigation' => true,
                    'destinations' => [
                        'destination_1' => true,
                        'destination_2' => true,
                    ],
                ],
                $navigator
            )
        );

        $this->assertEquals(
            null,
            $this->pathType->resolveDestination(
                [
                    'source' => 'step1',
                    'default_destination' => 'default',
                    'stop_navigation' => '{{ flow_data.data.step1.firstname == "john" }}',
                    'destinations' => [
                        'destination_1' => true,
                        'destination_2' => true,
                    ],
                ],
                $navigator
            )
        );
    }

    public function testbuildPath()
    {
        $step1 = new Step('step1', new HtmlStepType());
        $step2 = new Step('step2', new HtmlStepType());
        $step3 = new Step('step3', new HtmlStepType());
        $stepEnd = new Step('stepEnd', new HtmlStepType());

        $path = $this->pathType->buildPath(
            [
                'step1' => $step1,
                'step2' => $step2,
                'step3' => $step3,
                'stepEnd' => $stepEnd,
            ],
            [
                'source' => 'step1',
                'default_destination' => 'stepEnd',
                'destinations' => [
                    'step2' => false,
                    'step3' => false,
                ],
            ]
        );

        $this->assertEquals($path->getSource(), $step1);
        $this->assertEquals(
            $path->getDestinations(),
            [
                'step2' => $step2,
                'step3' => $step3,
                'stepEnd' => $stepEnd,
            ]
        );

        $flowData = $this->createMock(FlowDataInterface::class);
        $flowData
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue(null))
        ;
        $flow = $this->createMock(FlowInterface::class);
        $flow
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue($flowData))
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
