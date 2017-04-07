<?php

namespace IDCI\Bundle\StepBundle\Tests\Step;

use IDCI\Bundle\StepBundle\Step\Step;

class StepTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->testConfiguration = array(
            'name'    => 'Step test',
            'type'    => 'html',
            'options' => array(
                'data'             => array(),
                'is_first'         => false,
                'pre_step_content' => '<p>HTML content</p>',
            )
        );

        $this->step = new Step($this->testConfiguration);
    }

    public function testConfiguration()
    {
        $this->assertEquals($this->testConfiguration, $this->step->getConfiguration());
    }

    public function testType()
    {
        $this->assertEquals('html', $this->step->getType());
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
