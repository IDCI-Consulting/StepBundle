<?php

namespace IDCI\Bundle\StepBundle\Tests\Map;

use IDCI\Bundle\StepBundle\Map\MapBuilderFactoryInterface;
use PHPUnit\Framework\TestCase;

class MapBuilderTest extends TestCase
{
    public function setUp()
    {
        require_once __DIR__.'/../AppKernel.php';
        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $this->container = $kernel->getContainer();
    }

    public function testGetMap()
    {
        $request = $this->createMock("Symfony\Component\HttpFoundation\Request");
        $session = $this->createMock("Symfony\Component\HttpFoundation\Session\Session");

        $request->expects($this->any())
            ->method('getSession')
            ->will($this->returnValue($session))
        ;

        // Default map 0
        $mapBuilder0 = $this->container
            ->get(MapBuilderFactoryInterface::class)
            ->createNamedBuilder('Test default MAP 0')
        ;

        $map0 = $mapBuilder0->getMap($request);

        $this->assertInstanceOf('IDCI\Bundle\StepBundle\Map\MapInterface', $map0);
        $this->assertEquals('Test default MAP 0', $map0->getName());
        $this->assertFalse($map0->isDisplayStepInUrlEnabled());
        $this->assertFalse($map0->isResetFlowDataOnInitEnabled());
        // Default map 1
        $mapBuilder1 = $this->container
            ->get(MapBuilderFactoryInterface::class)
            ->createNamedBuilder('Test default MAP 1', array(), array(
                'display_step_in_url' => true,
                'reset_flow_data_on_init' => true,
            ))
        ;

        $map1 = $mapBuilder1->getMap($request);

        $this->assertInstanceOf('IDCI\Bundle\StepBundle\Map\MapInterface', $map1);
        $this->assertEquals('Test default MAP 1', $map1->getName());
        $this->assertTrue($map1->isDisplayStepInUrlEnabled());
        $this->assertTrue($map1->isResetFlowDataOnInitEnabled());

        /*
        $this
            ->mapBuilder
            ->addStep('intro', 'html', array(
                    'title'       => 'Introduction',
                    'description' => 'The first step',
                    'content'     => '<h1>My content</h1>',
                ))
            ->addStep('end', 'html', array(
                'title'       => 'The end',
                'description' => 'The last data step',
                'content'     => '<h1>The end</h1>',
            ))
            ->addPath('single', array(
                'source'       => 'intro',
                'destination'  => 'end',
                'next_options' => array(
                    'label' => 'next',
                ),
            ))
            ->addPath('end', array(
                'source'           => 'end',
                'next_options'     => array(
                    'label' => 'end',
                ),
            ))
        ;

        $map = $this
            ->mapBuilder
            ->getMap($request)
        ;
        */
    }
}
