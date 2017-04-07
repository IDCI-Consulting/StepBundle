<?php

namespace IDCI\Bundle\StepBundle\Tests\Path\Type;

use IDCI\Bundle\StepBundle\Path\Type\EndPathType;
use IDCI\Bundle\StepBundle\Step\Step;

class EndPathTypeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->pathType = new EndPathType();
    }

    public function testResolveDestination()
    {
        $navigator = $this->getMock("IDCI\Bundle\StepBundle\Navigation\NavigatorInterface");

        $this->assertEquals(
            null,
            $this->pathType->resolveDestination(
                array('source' => 'step1'),
                $navigator
            )
        );
    }

    public function testbuildPath()
    {
        $step1   = new Step(array('name' => 'step1'));
        $step2   = new Step(array('name' => 'step2'));
        $step3   = new Step(array('name' => 'step3'));
        $stepEnd = new Step(array('name' => 'stepEnd'));


        $path = $this->pathType->buildPath(
            array(
                'step1'   => $step1,
                'step2'   => $step2,
                'step3'   => $step3,
                'stepEnd' => $stepEnd,
            ),
            array('source' => 'stepEnd')
        );

        $this->assertEquals($path->getSource(), $stepEnd);
        $this->assertEquals(
            $path->getDestinations(),
            array()
        );

        $flow = $this->getMock("IDCI\Bundle\StepBundle\Flow\FlowInterface");
        $flow
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue(null))
        ;
        $navigator = $this->getMock("IDCI\Bundle\StepBundle\Navigation\NavigatorInterface");
        $navigator
            ->expects($this->any())
            ->method('getFlow')
            ->will($this->returnValue($flow))
        ;
        $this->assertEquals($path->resolveDestination($navigator), null);
    }
}
