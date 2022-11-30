<?php

namespace IDCI\Bundle\StepBundle\Tests\Step;

use IDCI\Bundle\StepBundle\Step\Type\HtmlStepType;

class StepTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->testOptions = [
            'data' => [],
            'is_first' => false,
            'pre_step_content' => '<p>HTML content</p>',
        ];

        $this->step = (new HtmlStepType())->buildStep('Step test', $this->testOptions);
    }

    public function testOptions()
    {
        $this->assertEquals($this->testOptions, $this->step->getOptions());
    }

    public function testType()
    {
        $this->assertInstanceOf(HtmlStepType::class, $this->step->getType());
    }

    public function testName()
    {
        $this->assertEquals('Step test', $this->step->getName());
    }

    public function testIsFirst()
    {
        $this->assertFalse($this->step->isFirst());
    }

    public function testPreStepContent()
    {
        $this->assertEquals('<p>HTML content</p>', $this->step->getPreStepContent());
    }
}
