<?php

namespace IDCI\Bundle\StepBundle\Tests\Navigation;

use IDCI\Bundle\StepBundle\Navigation\Navigator;
use IDCI\Bundle\StepBundle\Flow\Flow;
use IDCI\Bundle\StepBundle\Flow\FlowHistory;
use IDCI\Bundle\StepBundle\Flow\FlowData;
use IDCI\Bundle\StepBundle\Map\Map;
use IDCI\Bundle\StepBundle\Step\Step;

class NavigatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $map = new Map(array(
            'name'      => 'Test Map',
            'footprint' => 'DUMMYF00TPR1NT',
            'options'   => array(
                'browsing'          => 'linear',
                'first_step_name'   => 'stepA',
                'final_destination' => null,
            ),
            'data'      => array(
                'stepA' => array(
                    'field1' => 'MapStepAField1Value',
                    'field2' => 'MapStepAField2Value',
                ),
                'stepB' => array(
                    'field1' => 'MapStepBField1Value',
                    'field2' => 'MapStepBField2Value',
                )
            ),
        ));

        $stepType = $this->getMock("IDCI\Bundle\StepBundle\Step\Type\StepTypeInterface");

        $map
            ->addStep('stepA', new Step(array(
                'name'    => 'stepA',
                'type'    => $stepType,
                'options' => array(
                    'data' => array(
                        'field1' => 'StepAField1Value',
                    )
                )
            )))
            ->addStep('stepB', new Step(array(
                'name'    => 'stepB',
                'type'    => $stepType,
            )))
            ->addStep('stepC', new Step(array(
                'name'    => 'stepC',
                'type'    => $stepType,
                'options' => array(
                    'data' => array(
                        'field1' => 'StepCField1Value',
                    )
                )
            )))
            ->addStep('stepD', new Step(array(
                'name'    => 'stepD',
                'type'    => $stepType,
            )))
        ;

        $flowRecorder = $this->getMock("IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface");
        $flowRecorder
            ->expects($this->any())
            ->method('getFlow')
            ->will($this->returnValue(null))
        ;

        $formBuilder = $this->getMockBuilder("Symfony\Component\Form\FormBuilder")
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $formBuilder
            ->expects($this->any())
            ->method('getForm')
            ->will($this->returnValue(null))
        ;

        $formFactory = $this->getMock("Symfony\Component\Form\FormFactoryInterface");
        $formFactory->expects($this->any())
            ->method('createBuilder')
            ->will($this->returnValue($formBuilder))
        ;

        $this->navigator = new Navigator(
            $formFactory,
            $flowRecorder,
            $map,
            $this->getMock("Symfony\Component\HttpFoundation\Request"),
            $this->getMock("IDCI\Bundle\StepBundle\Navigation\NavigationLoggerInterface"),
            array()
        );
    }

    public function testNavigation()
    {
        // Initial state
        $this->assertFalse($this->navigator->hasNavigated());
        $this->assertFalse($this->navigator->hasReturned());
        $this->assertFalse($this->navigator->hasFinished());
        $this->assertEquals(
            array(
                'remindedData'  => array(),
                'retrievedData' => array()
            ),
            $this->navigator->getData()
        );

        // Navigate
        $this->navigator->navigate();

        // Tests flow data after that function 'initFlow()' was called.
        $this->assertEquals(
            array(
                'remindedData'  => array(
                    'stepA' => array(
                        'field1' => 'StepAField1Value',
                        'field2' => 'MapStepAField2Value',
                    ),
                    'stepB' => array(
                        'field1' => 'MapStepBField1Value',
                        'field2' => 'MapStepBField2Value',
                    ),
                    'stepC' => array(
                        'field1' => 'StepCField1Value',
                    ),
                    'stepD' => array(),
                ),
                'retrievedData' => array(
                    'stepA' => array(),
                    'stepB' => array(),
                    'stepC' => array(),
                    'stepD' => array(),
                )
            ),
            $this->navigator->getData()
        );
    }
}