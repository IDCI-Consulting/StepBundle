<?php

namespace IDCI\Bundle\StepBundle\Tests\Map;

use IDCI\Bundle\StepBundle\Map\MapBuilder;

class MapBuilderTest extends \PHPUnit_Framework_TestCase
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

        $twigStringLoader = new \Twig_Loader_String;
        $twigEnvironment  = new \Twig_Environment($twigStringLoader, array());

        $this->mapBuilder = new MapBuilder(
            "Test MAP",
            array(),
            array(),
            $this->getMock("IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface"),
            $this->getMock("IDCI\Bundle\StepBundle\Step\StepBuilderInterface"),
            $this->getMock("IDCI\Bundle\StepBundle\Path\PathBuilderInterface"),
            $twigEnvironment,
            $securityContext,
            $this->getMock("Symfony\Component\HttpFoundation\Session\SessionInterface")
        );
    }

    public function testGetMap()
    {
        $map = $this
            ->mapBuilder
            ->getMap($this->getMock("Symfony\Component\HttpFoundation\Request"))
        ;

        $this->assertInstanceOf('IDCI\Bundle\StepBundle\Map\MapInterface', $map);
        $this->assertEquals('Test MAP', $map->getName());

        /*
        $this
            ->mapBuilder
            ->addStep('intro', 'html', array(
                    'title'       => 'Introduction',
                    'description' => 'The first step',
                    'content'     => '<h1>My content</h1>',
                ))
            ->addStep('end', 'html', array(
                'title'       => 'The end',
                'description' => 'The last data step',
                'content'     => '<h1>The end</h1>',
            ))
            ->addPath('single', array(
                'source'       => 'intro',
                'destination'  => 'end',
                'next_options' => array(
                    'label' => 'next',
                ),
            ))
            ->addPath('end', array(
                'source'           => 'end',
                'next_options'     => array(
                    'label' => 'end',
                ),
            ))
        ;

        $map = $this
            ->mapBuilder
            ->getMap($this->getMock("Symfony\Component\HttpFoundation\Request"))
        ;
        */
    }
}
