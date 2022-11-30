<?php

namespace IDCI\Bundle\StepBundle\Tests\Path;

use IDCI\Bundle\StepBundle\Path\Type\SinglePathType;
use IDCI\Bundle\StepBundle\Step\Step;
use IDCI\Bundle\StepBundle\Step\Type\HtmlStepType;

class PathTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $step1 = new Step('step1', new HtmlStepType());
        $step2 = new Step('step2', new HtmlStepType());

        $steps = [
            'step1' => $step1,
            'step2' => $step2,
        ];

        $this->testOptions = [
            'source' => 'step1',
            'destination' => 'step2',
        ];

        $this->path = (new SinglePathType())->buildPath($steps, $this->testOptions);
    }

    public function testOptions()
    {
        $this->assertEquals($this->testOptions, $this->path->getOptions());
    }

    public function testType()
    {
        $this->assertInstanceOf(SinglePathType::class, $this->path->getType());
    }

    public function testSource()
    {
        $this->assertEquals('step1', $this->path->getSource()->getName());
    }

    public function testDestination()
    {
        $this->assertEquals(1, count($this->path->getDestinations()));
    }
}
