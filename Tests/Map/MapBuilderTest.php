<?php

namespace IDCI\Bundle\StepBundle\Tests\Map;

use IDCI\Bundle\StepBundle\Map\MapBuilder;
use PHPUnit\Framework\TestCase;

class MapBuilderTest extends TestCase
{
    public function setUp()
    {
        require_once __DIR__.'/../AppKernel.php';
        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $this->container = $kernel->getContainer();

        $this->mapBuilder = $this->container
            ->get('idci_step.map.builder.factory')
            ->createNamedBuilder('Test MAP')
        ;
    }

    public function testGetMap()
    {
        $request = $this->createMock("Symfony\Component\HttpFoundation\Request");
        $session = $this->createMock("Symfony\Component\HttpFoundation\Session\Session");

        $request->expects($this->any())
            ->method('getSession')
            ->will($this->returnValue($session))
        ;

        $map = $this->mapBuilder->getMap($request);

        $this->assertInstanceOf('IDCI\Bundle\StepBundle\Map\MapInterface', $map);
        $this->assertEquals('Test MAP', $map->getName());

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
    }
}
