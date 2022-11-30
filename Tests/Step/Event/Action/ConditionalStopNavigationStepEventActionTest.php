<?php

namespace IDCI\Bundle\StepBundle\Tests\Step\Event\Action;

use IDCI\Bundle\StepBundle\ConditionalRule\ConditionalRuleRegistry;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Step\Event\Action\ConditionalStopNavigationStepEventAction;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ConditionalStopNavigationStepEventActionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->event = $this->createMock(StepEventInterface::class);
        $this->event
            ->expects($this->any())
            ->method('getNavigator')
            ->will($this->returnValue($this->createMock(NavigatorInterface::class)))
        ;

        $registry = new ConditionalRuleRegistry();
        $router = $this->createMock(UrlGeneratorInterface::class);

        $this->eventAction = new ConditionalStopNavigationStepEventAction($registry, $router);
    }

    public function testExecute()
    {
        $this->assertTrue($this->eventAction->execute($this->event, [
            'rules' => true,
        ]));

        $this->assertFalse($this->eventAction->execute($this->event, [
            'rules' => false,
        ]));

        try {
            $this->eventAction->execute($this->event, [
                'rules' => ['dummy' => []],
            ]);

            $this->fail('Expected exception InvalidArgumentException not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }
}
