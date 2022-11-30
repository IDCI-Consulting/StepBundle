<?php

namespace IDCI\Bundle\StepBundle\Tests\Path\Type;

use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\Type\EndPathType;
use IDCI\Bundle\StepBundle\Step\Step;
use IDCI\Bundle\StepBundle\Step\Type\HtmlStepType;

class EndPathTypeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->pathType = new EndPathType();
    }

    public function testResolveDestination()
    {
        $navigator = $this->createMock(NavigatorInterface::class);

        $this->assertEquals(
            null,
            $this->pathType->resolveDestination(
                ['source' => 'step1'],
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
            ['source' => 'stepEnd']
        );

        $this->assertEquals($stepEnd, $path->getSource());
        $this->assertEquals(
            [],
            $path->getDestinations()
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
        $this->assertEquals($path->resolveDestination($navigator), null);
    }
}
