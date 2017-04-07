<?php

namespace IDCI\Bundle\StepBundle\Tests\Map;

use IDCI\Bundle\StepBundle\Map\Map;
use IDCI\Bundle\StepBundle\Exception\StepNotFoundException;

class MapTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->map = new Map(array(
            'name'      => 'Test map',
            'data'      => array(),
            'footprint' => 'MyDummyFootPrint',
            'options'   => array(
                'first_step_name'   => 'step1',
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
        $step1 = $this->getMock("IDCI\Bundle\StepBundle\Step\StepInterface");
        $step1
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue(null))
        ;
        $this->map->addStep('step1', $step1);

        $this->assertTrue($this->map->hasStep('step1'));
        $this->assertFalse($this->map->hasStep('stepX'));
        $this->assertEquals(1, $this->map->countSteps());
        $this->assertEquals($step1, $this->map->getStep('step1'));

        $step2 = $this->getMock("IDCI\Bundle\StepBundle\Step\StepInterface");
        $step2
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue(null))
        ;
        $this->map->addStep('step2', $step2);

        $this->assertTrue($this->map->hasStep('step1'));
        $this->assertTrue($this->map->hasStep('step2'));
        $this->assertEquals(2, $this->map->countSteps());
        $this->assertEquals($step2, $this->map->getStep('step2'));

        try {
            $this->map->getStep('stepX');
            $this->fail("Expected exception StepNotFoundException not thrown");
        } catch (StepNotFoundException $e) {
            $this->assertTrue(true);
        }

        $this->assertEquals($step1, $this->map->getFirstStep());
    }

    public function testPath()
    {
        $step1 = $this->getMock("IDCI\Bundle\StepBundle\Step\StepInterface");
        $step1
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue(null))
        ;
        $this->map->addStep('step1', $step1);

        $step2 = $this->getMock("IDCI\Bundle\StepBundle\Step\StepInterface");
        $step2
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue(null))
        ;
        $this->map->addStep('step2', $step2);

        $path1 = $this->getMock("IDCI\Bundle\StepBundle\Path\PathInterface");
        $path2 = $this->getMock("IDCI\Bundle\StepBundle\Path\PathInterface");
        $pathE = $this->getMock("IDCI\Bundle\StepBundle\Path\PathInterface");

        $this->map->addPath('step1', $path1);
        $this->map->addPath('step2', $path2);
        $this->map->addPath('step2', $pathE);

        $this->assertEquals(
            array(
                'step1' => array($path1),
                'step2' => array($path2, $pathE)
            ),
            $this->map->getPaths()
        );

        $this->assertEquals(
            array($path2, $pathE),
            $this->map->getPaths('step2')
        );

        $this->assertEquals($path1, $this->map->getPath('step1', 0));
        $this->assertEquals($path2, $this->map->getPath('step2', 0));
        $this->assertEquals($pathE, $this->map->getPath('step2', 1));
    }

    public function testGetData()
    {
        $this->assertEquals(array(), $this->map->getData());
    }

    public function testGetConfiguration()
    {
        $this->assertEquals(
            array(
                'name'      => 'Test map',
                'data'      => array(),
                'footprint' => 'MyDummyFootPrint',
                'options'   => array(
                    'first_step_name'   => 'step1',
                )
            ),
            $this->map->getConfiguration()
        );
    }
}
