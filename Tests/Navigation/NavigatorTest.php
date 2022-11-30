<?php

namespace IDCI\Bundle\StepBundle\Tests\Navigation;

use IDCI\Bundle\StepBundle\Flow\FlowDataInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface;
use IDCI\Bundle\StepBundle\Map\Map;
use IDCI\Bundle\StepBundle\Navigation\NavigationLoggerInterface;
use IDCI\Bundle\StepBundle\Navigation\Navigator;
use IDCI\Bundle\StepBundle\Step\Step;
use IDCI\Bundle\StepBundle\Step\Type\StepTypeInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class NavigatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $map = new Map([
            'name' => 'Test Map',
            'footprint' => 'DUMMYF00TPR1NT',
            'options' => [
                'first_step_name' => 'stepA',
                'final_destination' => null,
                'display_step_in_url' => false,
                'reset_flow_data_on_init' => false,
            ],
            'data' => [
                'stepA' => [
                    'field1' => 'MapStepAField1Value',
                    'field2' => 'MapStepAField2Value',
                    'field3' => 'MapStepAField3Value',
                ],
                'stepB' => [
                    'field1' => 'MapStepBField1Value',
                    'field2' => 'MapStepBField2Value',
                ],
                'stepC' => [
                    'field2' => ['d', 'e', 'f'],
                ],
            ],
        ]);

        $stepType = $this->createMock(StepTypeInterface::class);

        $map
            ->addStep('stepA', new Step('stepA', $stepType, [
                'data' => [
                    'field1' => 'StepAField1Value',
                    'field2' => 'StepAField2Value',
                ],
            ]))
            ->addStep('stepB', new Step('stepB', $stepType))
            ->addStep('stepC', new Step('stepC', $stepType, [
                'data' => [
                    'field1' => 'StepCField1Value',
                    'field2' => ['a', 'b', 'c'],
                ],
            ]))
            ->addStep('stepD', new Step('stepD', $stepType))
        ;

        $flowData = $this->createMock(FlowDataInterface::class);
        $flowData
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue([]))
        ;
        $flow = $this->createMock(FlowInterface::class);
        $flow
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue($flowData))
        ;
        $flow
            ->expects($this->any())
            ->method('getCurrentStepName')
            ->will($this->returnValue('stepA'))
        ;
        $flowRecorder = $this->createMock(FlowRecorderInterface::class);
        $flowRecorder
            ->expects($this->any())
            ->method('getFlow')
            ->will($this->returnValue($flow))
        ;

        $form = $this->createMock(FormInterface::class);
        $formBuilder = $this->getMockBuilder(FormBuilder::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $formBuilder
            ->expects($this->any())
            ->method('getForm')
            ->will($this->returnValue($form))
        ;

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->expects($this->any())
            ->method('createBuilder')
            ->will($this->returnValue($formBuilder))
        ;

        $this->data = [
            'stepA' => [
                'field1' => 'FlowStepAField1Value',
            ],
            'stepC' => [
                'field2' => ['g', 'h', 'i'],
            ],
        ];

        $this->navigator = new Navigator(
            $formFactory,
            $flowRecorder,
            $map,
            $this->createMock(Request::class),
            $this->createMock(NavigationLoggerInterface::class),
            ['data' => $this->data]
        );
    }

    public function testNavigation()
    {
        // Initial state
        $this->assertFalse($this->navigator->hasNavigated());
        $this->assertFalse($this->navigator->hasReturned());
        $this->assertFalse($this->navigator->hasFinished());

        // Navigate
        $this->navigator->navigate();
    }

    public function testUrlQueryParameters()
    {
        $this->assertEquals([], $this->navigator->getUrlQueryParameters());

        $this->navigator
            ->addUrlQueryParameter('key1', 'value1')
            ->addUrlQueryParameter('key2', 'value2')
            ->addUrlQueryParameter('key3', 'value3')
        ;
        $this->assertEquals(
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3',
            ],
            $this->navigator->getUrlQueryParameters()
        );

        $this->navigator->addUrlQueryParameter('key1', 'value1-1');
        $this->assertEquals(
            [
                'key1' => 'value1-1',
                'key2' => 'value2',
                'key3' => 'value3',
            ],
            $this->navigator->getUrlQueryParameters()
        );
    }

    /*
    public function testUrlFragment()
    {
        $this->assertEquals(null, $this->navigator->getUrlFragment());

        $this->navigator->setUrlFragment('dummyfragment');
        $this->assertEquals(
            '#dummyfragment',
            $this->navigator->getUrlFragment()
        );

        $this->navigator->setUrlFragment('#dummyfragment');
        $this->assertEquals(
            '#dummyfragment',
            $this->navigator->getUrlFragment()
        );

        $this->navigator->setUrlFragment('#dummy#fragment');
        $this->assertEquals(
            '#dummy#fragment',
            $this->navigator->getUrlFragment()
        );
    }
    */
}
