<?php

namespace IDCI\Bundle\StepBundle\Tests\Navigation\Event;

use IDCI\Bundle\StepBundle\Flow\FlowDataInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Navigation\Event\NavigationEventSubscriber;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\Event\PathEventActionRegistryInterface;
use IDCI\Bundle\StepBundle\Step\Event\StepEventActionRegistryInterface;
use IDCI\Bundle\StepBundle\Twig\Environment;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NavigationEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $tokenStorage = $this
            ->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getToken', 'setToken'])
            ->getMock()
        ;
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null))
        ;

        $flowData = $this->createMock(FlowDataInterface::class);
        $flowData
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue([
                'step1' => [
                    'firstname' => 'john',
                    'lastname' => 'doe',
                    'message' => "multi\nline\nmessage.",
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

        $twigStringLoader = new \Twig_Loader_Array();
        $twigEnvironment = new Environment($twigStringLoader, []);

        $this->navigationEventSubscriber = new NavigationEventSubscriber(
            $navigator,
            $this->createMock(StepEventActionRegistryInterface::class),
            $this->createMock(PathEventActionRegistryInterface::class),
            $twigEnvironment,
            $tokenStorage,
            $this->createMock(SessionInterface::class)
        );
    }

    public function testMerge()
    {
        $parameters = [
            'string' => 'value1',
            'int' => 1000,
            'bool' => false,
            'array' => [
                'string' => 'value2',
            ],
        ];

        $this->assertEquals(
            $parameters,
            $this->navigationEventSubscriber->merge($parameters)
        );

        $parameters = [
            'firstname' => '{{ flow_data.data.step1.firstname }}',
            'lastname' => '{{ flow_data.data.step1.lastname }}',
            'message' => '{{ flow_data.data.step1.message }}',
        ];

        $this->assertEquals(
            [
                'firstname' => 'john',
                'lastname' => 'doe',
                'message' => "multi\nline\nmessage.",
            ],
            $this->navigationEventSubscriber->merge($parameters)
        );

        $parameters = [
            'user' => [
                'firstname' => '{{ flow_data.data.step1.firstname }}',
                'data' => [
                    'message' => '{{ flow_data.data.step1.message }}',
                ],
            ],
        ];

        $this->assertEquals(
            [
                'user' => [
                    'firstname' => 'john',
                    'data' => [
                        'message' => "multi\nline\nmessage.",
                    ],
                ],
            ],
            $this->navigationEventSubscriber->merge($parameters)
        );
    }
}
