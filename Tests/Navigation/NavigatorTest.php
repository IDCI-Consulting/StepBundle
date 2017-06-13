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
                'first_step_name'     => 'stepA',
                'final_destination'   => null,
                'display_step_in_url' => false,
            ),
            'data'      => array(
                'stepA' => array(
                    'field1' => 'MapStepAField1Value',
                    'field2' => 'MapStepAField2Value',
                    'field3' => 'MapStepAField3Value',
                ),
                'stepB' => array(
                    'field1' => 'MapStepBField1Value',
                    'field2' => 'MapStepBField2Value',
                ),
                'stepC' => array(
                    'field2' => array('d', 'e', 'f')
                ),
            ),
        ));

        $stepType = $this->createMock("IDCI\Bundle\StepBundle\Step\Type\StepTypeInterface");

        $map
            ->addStep('stepA', new Step(array(
                'name'    => 'stepA',
                'type'    => $stepType,
                'options' => array(
                    'data' => array(
                        'field1' => 'StepAField1Value',
                        'field2' => 'StepAField2Value',
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
                        'field2' => array('a', 'b', 'c')
                    )
                )
            )))
            ->addStep('stepD', new Step(array(
                'name'    => 'stepD',
                'type'    => $stepType,
            )))
        ;

        $flowRecorder = $this->createMock("IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface");
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

        $formFactory = $this->createMock("Symfony\Component\Form\FormFactoryInterface");
        $formFactory->expects($this->any())
            ->method('createBuilder')
            ->will($this->returnValue($formBuilder))
        ;

        $this->data = array(
            'stepA' => array(
                'field1' => 'FlowStepAField1Value'
            ),
            'stepC' => array(
                'field2' => array('g', 'h', 'i')
            ),
        );

        $this->navigator = new Navigator(
            $formFactory,
            $flowRecorder,
            $map,
            $this->createMock("Symfony\Component\HttpFoundation\Request"),
            $this->createMock("IDCI\Bundle\StepBundle\Navigation\NavigationLoggerInterface"),
            array('data' => $this->data)
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
        $this->assertEquals(array(), $this->navigator->getUrlQueryParameters());

        $this->navigator
            ->addUrlQueryParameter('key1', 'value1')
            ->addUrlQueryParameter('key2', 'value2')
            ->addUrlQueryParameter('key3', 'value3')
        ;
        $this->assertEquals(
            array(
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3',
            ),
            $this->navigator->getUrlQueryParameters()
        );

        $this->navigator->addUrlQueryParameter('key1', 'value1-1');
        $this->assertEquals(
            array(
                'key1' => 'value1-1',
                'key2' => 'value2',
                'key3' => 'value3',
            ),
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
