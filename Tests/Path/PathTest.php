<?php

namespace IDCI\Bundle\StepBundle\Tests\Path;

use IDCI\Bundle\StepBundle\Path\Path;

class PathTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->testConfiguration = array(
            'type' => 'single',
            'options' => array(),
        );

        $this->path = new Path($this->testConfiguration);
    }

    public function testConfiguration()
    {
        $this->assertEquals($this->testConfiguration, $this->path->getConfiguration());
    }

    public function testType()
    {
        $this->assertEquals('single', $this->path->getType());
    }

    public function testSource()
    {
        $step = $this->createMock("IDCI\Bundle\StepBundle\Step\StepInterface");
        $step
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('step1'))
        ;
        $this->path->setSource($step);

        $this->assertEquals('step1', $this->path->getSource()->getName());
    }

    public function testDestination()
    {
        $step1 = $this->createMock("IDCI\Bundle\StepBundle\Step\StepInterface");
        $step1
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('step1'))
        ;

        $step2 = $this->createMock("IDCI\Bundle\StepBundle\Step\StepInterface");
        $step2
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('step2'))
        ;

        $this->path
            ->addDestination($step1)
            ->addDestination($step2)
        ;

        $this->assertEquals(2, count($this->path->getDestinations()));
    }

    public function testOptions()
    {
        $this->assertEquals(array(), $this->path->getOptions());
    }
}
