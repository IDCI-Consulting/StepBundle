<?php

namespace IDCI\Bundle\StepBundle\Tests\Navigation;

use IDCI\Bundle\StepBundle\Navigation\Navigator;

class NavigatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $map = $this->getMock("IDCI\Bundle\StepBundle\Map\MapInterface");
        $map
            ->expects($this->once())
            ->method('getData')
            ->will($this->returnValue(array()))
        ;

        $this->navigator = new Navigator(
            $this->getMock("Symfony\Component\Form\FormFactoryInterface"),
            $this->getMock("IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface"),
            $map,
            $this->getMock("Symfony\Component\HttpFoundation\Request"),
            $this->getMock("IDCI\Bundle\StepBundle\Navigation\NavigationLoggerInterface"),
            array()
        );
    }

    public function testNavigation()
    {
        $this->assertFalse($this->navigator->hasNavigated());
        $this->assertFalse($this->navigator->hasReturned());
        $this->assertFalse($this->navigator->hasFinished());
    }
}