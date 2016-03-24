<?php

namespace IDCI\Bundle\StepBundle\Tests\Path\Type;

use IDCI\Bundle\StepBundle\Path\Type\SinglePathType;
use IDCI\Bundle\StepBundle\Step\Step;

class SinglPathTypeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->pathType = new SinglePathType();
    }

    public function testResolveDestination()
    {
        $navigator = $this->getMock("IDCI\Bundle\StepBundle\Navigation\NavigatorInterface");

        $this->assertEquals(
            'next_step',
            $this->pathType->resolveDestination(
                array(
                    'source'      => 'step1',
                    'destination' => 'next_step'
                ),
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
            array(
                'source'      => 'step2',
                'destination' => 'step3'
            )
        );

        $this->assertEquals($path->getSource(), $step2);
        $this->assertEquals(
            $path->getDestinations(),
            array('step3' => $step3)
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
        $this->assertEquals($path->resolveDestination($navigator), $step3);
    }
}