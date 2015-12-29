<?php

namespace IDCI\Bundle\StepBundle\Tests\Navigation\Event;

use IDCI\Bundle\StepBundle\Navigation\Event\NavigationEventSubscriber;

class NavigationEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $securityContext = $this
            ->getMockBuilder("Symfony\Component\Security\Core\SecurityContextInterface")
            ->disableOriginalConstructor()
            ->setMethods(array('getToken', 'setToken', 'isGranted'))
            ->getMock()
        ;
        $securityContext
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null))
        ;

        $flow = $this->getMock("IDCI\Bundle\StepBundle\Flow\FlowInterface");
        $flow
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue(array(
                'data' => array(
                    'step1' => array(
                        'firstname' => "john",
                        'lastname'  => "doe",
                        'message'   => "multi\nline\nmessage.",
                    )
                )
            )))
        ;
        $navigator = $this->getMock("IDCI\Bundle\StepBundle\Navigation\NavigatorInterface");
        $navigator
            ->expects($this->any())
            ->method('getFlow')
            ->will($this->returnValue($flow))
        ;

        $twigStringLoader = new \Twig_Loader_String;
        $twigEnvironment  = new \Twig_Environment($twigStringLoader, array());

        $this->navigationEventSubscriber = new NavigationEventSubscriber(
            $navigator,
            $this->getMock("IDCI\Bundle\StepBundle\Step\Event\StepEventRegistryInterface"),
            $this->getMock("IDCI\Bundle\StepBundle\Path\Event\PathEventRegistryInterface"),
            $twigEnvironment,
            $securityContext,
            $this->getMock("Symfony\Component\HttpFoundation\Session\SessionInterface")
        );
    }

    public function testMerge()
    {
        $parameters = array(
            'string' => 'value1',
            'int'    => 1000,
            'bool'   => false,
            'array'  => array(
                'string' => 'value2'
            )
        );

        $this->assertEquals(
            $parameters,
            $this->navigationEventSubscriber->merge($parameters)
        );

        $parameters = array(
            'firstname' => '{{ flow_data.data.step1.firstname }}',
            'lastname'  => '{{ flow_data.data.step1.lastname }}',
            'message'   => '{{ flow_data.data.step1.message }}',
        );

        $this->assertEquals(
            array(
                'firstname' => "john",
                'lastname'  => "doe",
                'message' => "multi\nline\nmessage."
            ),
            $this->navigationEventSubscriber->merge($parameters)
        );

        $parameters = array(
            'user' => array(
                'firstname' => '{{ flow_data.data.step1.firstname }}',
                'data'      => array(
                    'message' => '{{ flow_data.data.step1.message }}'
                )
            )
        );

        $this->assertEquals(
           array(
                'user' => array(
                    'firstname' => "john",
                    'data'      => array(
                        'message' => "multi\nline\nmessage."
                    )
                )
            ),
            $this->navigationEventSubscriber->merge($parameters)
        );
    }
}