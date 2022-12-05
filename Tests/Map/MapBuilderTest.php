<?php

namespace IDCI\Bundle\StepBundle\Tests\Map;

use IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface;
use IDCI\Bundle\StepBundle\Map\MapBuilder;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Path\PathBuilderInterface;
use IDCI\Bundle\StepBundle\Step\StepBuilderInterface;
use IDCI\Bundle\StepBundle\Twig\Environment;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Template;
use Twig\TemplateWrapper;

class MapBuilderTest extends TestCase
{
    public function setUp()
    {
        $this->flowRecorder = $this->createMock(FlowRecorderInterface::class);
        $this->stepBuilder = $this->createMock(StepBuilderInterface::class);
        $this->pathBuilder = $this->createMock(PathBuilderInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->request = $this->createMock(Request::class);
        $template = $this->createMock(Template::class);
        $template
            ->expects($this->any())
            ->method('render')
            ->will($this->returnValue(''))
        ;
        $this->merger = $this->createMock(Environment::class);
        $this->merger
            ->expects($this->any())
            ->method('createTemplate')
            ->will($this->returnValue(new TemplateWrapper($this->merger, $template)))
        ;
    }

    public function testGetMap()
    {
        // Map Builder options
        $mapBuilder0 = new MapBuilder(
            $this->flowRecorder,
            $this->stepBuilder,
            $this->pathBuilder,
            $this->merger,
            $this->tokenStorage,
            $this->session,
            'Test default MAP 0'
        );
        $map0 = $mapBuilder0->getMap($this->request);
        $this->assertInstanceOf(MapInterface::class, $map0);
        $this->assertEquals('Test default MAP 0', $map0->getName());
        $this->assertFalse($map0->isDisplayStepInUrlEnabled());
        $this->assertFalse($map0->isResetFlowDataOnInitEnabled());
        $this->assertEquals(0, $map0->countSteps());

        $mapBuilder1 = new MapBuilder(
            $this->flowRecorder,
            $this->stepBuilder,
            $this->pathBuilder,
            $this->merger,
            $this->tokenStorage,
            $this->session,
            'Test default MAP 1',
            [],
            [
                'display_step_in_url' => true,
                'reset_flow_data_on_init' => true,
            ]
        );
        $map1 = $mapBuilder1->getMap($this->request);
        $this->assertInstanceOf(MapInterface::class, $map1);
        $this->assertEquals('Test default MAP 1', $map1->getName());
        $this->assertTrue($map1->isDisplayStepInUrlEnabled());
        $this->assertTrue($map1->isResetFlowDataOnInitEnabled());
        $this->assertEquals(0, $map1->countSteps());

        // Map Builder Step and Path
        $mapBuilder1
            ->addStep('intro', 'html', [
                'title' => 'Introduction',
                'description' => 'The first step',
                'content' => '<h1>My content</h1>',
            ])
            ->addStep('end', 'html', [
                'title' => 'The end',
                'description' => 'The last data step',
                'content' => '<h1>The end</h1>',
            ])
            ->addPath('single', [
                'source' => 'intro',
                'destination' => 'end',
                'next_options' => [
                    'label' => 'next',
                ],
            ])
            ->addPath('end', [
                'source' => 'end',
                'next_options' => [
                    'label' => 'end',
                ],
            ])
        ;
        $map2 = $mapBuilder1->getMap($this->request);
        $this->assertEquals(2, $map2->countSteps());
        $this->assertTrue($map2->hasStep('intro'));
        $this->assertTrue($map2->hasStep('end'));
        $this->assertFalse($map2->hasStep('other'));
    }
}
