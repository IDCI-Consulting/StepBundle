<?php

namespace IDCI\Bundle\StepBundle\Tests\Navigation\Event;

use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Navigation\Event\NavigationEventSubscriber;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\Event\PathEventActionRegistryInterface;
use IDCI\Bundle\StepBundle\Step\Event\StepEventActionRegistryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class NavigationEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $tokenStorage = $this
            ->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getToken', 'setToken'))
            ->getMock()
        ;
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null))
        ;

        $flow = $this->createMock(FlowInterface::class);
        $flow
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue(array(
                'data' => array(
                    'step1' => array(
                        'firstname' => 'john',
                        'lastname' => 'doe',
                        'message' => "multi\nline\nmessage.",
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

        $twigStringLoader = new \Twig_Loader_Array();
        $twigEnvironment = new Environment($twigStringLoader, array());

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
        $parameters = array(
            'string' => 'value1',
            'int' => 1000,
            'bool' => false,
            'array' => array(
                'string' => 'value2',
            ),
        );

        $this->assertEquals(
            $parameters,
            $this->navigationEventSubscriber->merge($parameters)
        );

        $parameters = array(
            'firstname' => '{{ flow_data.data.step1.firstname }}',
            'lastname' => '{{ flow_data.data.step1.lastname }}',
            'message' => '{{ flow_data.data.step1.message }}',
        );

        $this->assertEquals(
            array(
                'firstname' => 'john',
                'lastname' => 'doe',
                'message' => "multi\nline\nmessage.",
            ),
            $this->navigationEventSubscriber->merge($parameters)
        );

        $parameters = array(
            'user' => array(
                'firstname' => '{{ flow_data.data.step1.firstname }}',
                'data' => array(
                    'message' => '{{ flow_data.data.step1.message }}',
                ),
            ),
        );

        $this->assertEquals(
            array(
                'user' => array(
                    'firstname' => 'john',
                    'data' => array(
                        'message' => "multi\nline\nmessage.",
                    ),
                ),
            ),
            $this->navigationEventSubscriber->merge($parameters)
        );
    }
}
