<?php

namespace IDCI\Bundle\StepBundle\Tests\Map;

use IDCI\Bundle\StepBundle\Map\Map;

class MapTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->map = new Map(array(
            'name'      => 'Test map',
            'data'      => array(),
            'footprint' => 'MyDummyFootPrint',
            'options'   => array(
                'first_step_name' => 'step1',
            )
        ));
    }

    public function testFirstStepName()
    {
        $this->assertEquals('step1', $this->map->getFirstStepName());
    }

    public function testFootprint()
    {
        $this->assertEquals('MyDummyFootPrint', $this->map->getFootprint());
    }

    public function testStep()
    {
        $step = $this->getMock("IDCI\Bundle\StepBundle\Step\StepInterface");
        $step
            ->expects($this->once())
            ->method('getData')
            ->will($this->returnValue(null))
        ;

        $this->map->addStep('step1', $step);

        $this->assertTrue($this->map->hasStep('step1'));
        $this->assertEquals(1, $this->map->countSteps());
    }

    public function testPath()
    {
        $step = $this->getMock("IDCI\Bundle\StepBundle\Step\StepInterface");
        $step
            ->expects($this->once())
            ->method('getData')
            ->will($this->returnValue(null))
        ;

        $path = $this->getMock("IDCI\Bundle\StepBundle\Path\PathInterface");

        $this->map->addStep('step1', $step);
        $this->map->addPath('step1', $path);
    }
}