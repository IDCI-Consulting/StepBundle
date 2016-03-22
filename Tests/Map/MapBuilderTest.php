<?php

namespace IDCI\Bundle\StepBundle\Tests\Map;

use IDCI\Bundle\StepBundle\Map\MapBuilder;

class MapBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $securityContext = $this
            ->getMockBuilder('Symfony\Component\Security\Core\SecurityContextInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $securityContext
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null))
        ;

        $this->mapBuilder = new MapBuilder(
            "Test MAP",
            array(),
            array(),
            $this->getMock("IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface"),
            $this->getMock("IDCI\Bundle\StepBundle\Step\StepBuilderInterface"),
            $this->getMock("IDCI\Bundle\StepBundle\Path\PathBuilderInterface"),
            $this->getMock("\Twig_Environment"),
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
    }
}