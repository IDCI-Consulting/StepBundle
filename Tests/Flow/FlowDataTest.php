<?php

namespace IDCI\Bundle\StepBundle\Tests\Flow;

use IDCI\Bundle\StepBundle\Flow\FlowData;

class FlowDataTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->flowData = new FlowData(
            array(),
            array(),
            array()
        );
    }

    public function testData()
    {
        $data = array(
            'step1' => array(
                'firstname' => 'John',
                'lastname'  => 'DOE',
            )
        );

        $this->flowData->setData($data);
        $this->flowData->setRemindedData($data);
        $this->flowData->setRetrievedData($data);

        $this->assertEquals($data, $this->flowData->getData());
        $this->assertEquals($data, $this->flowData->getRemindedData());
        $this->assertEquals($data, $this->flowData->getRetrievedData());
    }

    public function testStepData()
    {
        $step1Data = array(
            'firstname' => 'John',
            'lastname'  => 'DOE',
        );

        $step2Data = array(
            'phonenumber' => '0123456789',
            'age'         => '42',
        );

        $this->flowData
            ->setStepData('step1', $step1Data)
            ->setStepData('step2', $step2Data)
        ;
        $this->assertEquals($step1Data, $this->flowData->getStepData('step1'));
        $this->assertEquals($step2Data, $this->flowData->getStepData('step2'));
        $this->assertTrue($this->flowData->hasStepData('step1'));
        $this->assertFalse($this->flowData->hasStepData('stepX'));

        $this->flowData
            ->setStepData('step1', $step1Data, FlowData::TYPE_REMINDED)
            ->setStepData('step2', $step2Data, FlowData::TYPE_REMINDED)
        ;
        $this->assertEquals($step1Data, $this->flowData->getStepData('step1'), FlowData::TYPE_REMINDED);
        $this->assertEquals($step2Data, $this->flowData->getStepData('step2'), FlowData::TYPE_REMINDED);
        $this->assertTrue($this->flowData->hasStepData('step1', FlowData::TYPE_REMINDED));
        $this->assertFalse($this->flowData->hasStepData('stepX', FlowData::TYPE_REMINDED));

        $this->flowData
            ->setStepData('step1', $step1Data, FlowData::TYPE_RETRIEVED)
            ->setStepData('step2', $step2Data, FlowData::TYPE_RETRIEVED)
        ;
        $this->assertEquals($step1Data, $this->flowData->getStepData('step1'), FlowData::TYPE_RETRIEVED);
        $this->assertEquals($step2Data, $this->flowData->getStepData('step2'), FlowData::TYPE_RETRIEVED);
        $this->assertTrue($this->flowData->hasStepData('step1', FlowData::TYPE_RETRIEVED));
        $this->assertFalse($this->flowData->hasStepData('stepX', FlowData::TYPE_RETRIEVED));

        $this->flowData
            ->unsetStepData('step2')
            ->unsetStepData('step2', FlowData::TYPE_REMINDED)
            ->unsetStepData('step2', FlowData::TYPE_RETRIEVED)
        ;

        $this->assertFalse($this->flowData->hasStepData('step2'));
        $this->assertFalse($this->flowData->hasStepData('step2', FlowData::TYPE_REMINDED));
        $this->assertFalse($this->flowData->hasStepData('step2', FlowData::TYPE_RETRIEVED));
    }
}