<?php

namespace IDCI\Bundle\StepBundle\Tests\Step;

use IDCI\Bundle\StepBundle\Step\Event\Action\ConditionalStopNavigationStepEventAction;
use IDCI\Bundle\StepBundle\ConditionalRule\ConditionalRuleRegistry;
use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;

class ConditionalStopNavigationStepEventActionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->event = $this->createMock("IDCI\Bundle\StepBundle\Step\Event\StepEventInterface");
        $this->event
            ->expects($this->any())
            ->method('getNavigator')
            ->will($this->returnValue($this->createMock("IDCI\Bundle\StepBundle\Navigation\NavigatorInterface")))
        ;

        $registry = new ConditionalRuleRegistry();

        $this->eventAction = new ConditionalStopNavigationStepEventAction($registry);
    }

    public function testExecute()
    {
        $this->assertTrue($this->eventAction->execute($this->event, array(
            'rules' => true,
        )));

        $this->assertFalse($this->eventAction->execute($this->event, array(
            'rules' => false,
        )));

        try {
            $this->eventAction->execute($this->event, array(
                'rules' => array(1234 => array()),
            ));

            $this->fail('Expected exception UnexpectedTypeException not thrown');
        } catch (UnexpectedTypeException $e) {
            $this->assertTrue(true);
        }

        try {
            $this->eventAction->execute($this->event, array(
                'rules' => array('dummy' => array()),
            ));

            $this->fail('Expected exception InvalidArgumentException not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }
}
