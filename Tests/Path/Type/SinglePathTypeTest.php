<?php

namespace IDCI\Bundle\StepBundle\Tests\Path\Type;

use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\Type\SinglePathType;
use IDCI\Bundle\StepBundle\Step\Step;
use IDCI\Bundle\StepBundle\Step\Type\HtmlStepType;

class SinglePathTypeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->pathType = new SinglePathType();
    }

    public function testResolveDestination()
    {
        $navigator = $this->createMock(NavigatorInterface::class);

        $this->assertEquals(
            'next_step',
            $this->pathType->resolveDestination(
                [
                    'source' => 'step1',
                    'destination' => 'next_step',
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
                'source' => 'step2',
                'destination' => 'step3',
            ]
        );

        $this->assertEquals($path->getSource(), $step2);
        $this->assertEquals(
            $path->getDestinations(),
            ['step3' => $step3]
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
        $this->assertEquals($path->resolveDestination($navigator), $step3);
    }
}
