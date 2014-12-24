<?php

namespace IDCI\Bundle\StepBundle\Tests\Flow;

use IDCI\Bundle\StepBundle\Flow\FlowHistory;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class FlowHistoryTest extends \PHPUnit_Framework_TestCase
{
    private static $flowHistory;

    public static function setUpBeforeClass()
    {
        self::$flowHistory = new FlowHistory();
    }

    public function getAddData()
    {
        $data = array();

        # 0
        $data[] = array(
            'plap',
            'blab'
        );

        # 1
        $data[] = array(
            'plep',
            'bleb'
        );

        # 2
        $data[] = array(
            'plip',
            'blib'
        );

        # 3
        $data[] = array(
            'plop',
            'blob'
        );

        return $data;
    }

    public function getRetraceData()
    {
        $data = array();

        # 0
        $data[] = array(
            'plip',
            'blib',
            array('plip', 'plop'),
            array('blib', 'blob'),
            'plep',
            'bleb'
        );

        # 1
        $data[] = array(
            'plep',
            'bleb',
            array('plep'),
            array('bleb'),
            'plap',
            'blab'
        );

        return $data;
    }

    /**
     * @covers IDCI\Bundle\StepBundle\Flow\FlowHistory::addDoneStep
     * @covers IDCI\Bundle\StepBundle\Flow\FlowHistory::hasDoneStep
     * @covers IDCI\Bundle\StepBundle\Flow\FlowHistory::addTakenPath
     * @covers IDCI\Bundle\StepBundle\Flow\FlowHistory::hasTakenPath
     * @dataProvider getAddData
     */
    public function testAdd(
        $doneStep,
        $takenPath
    )
    {
        $flowHistory = self::$flowHistory;

        $this->assertEquals(false, $flowHistory->hasDoneStep($doneStep));
        $flowHistory->addDoneStep($doneStep);
        $this->assertEquals(true, $flowHistory->hasDoneStep($doneStep));

        $this->assertEquals(false, $flowHistory->hasTakenPath($takenPath));
        $flowHistory->addTakenPath($takenPath);
        $this->assertEquals(true, $flowHistory->hasTakenPath($takenPath));
    }

    /**
     * @covers IDCI\Bundle\StepBundle\Flow\FlowHistory::hasCanceledStep
     * @covers IDCI\Bundle\StepBundle\Flow\FlowHistory::retraceDoneStep
     * @covers IDCI\Bundle\StepBundle\Flow\FlowHistory::retraceTakenPath
     * @covers IDCI\Bundle\StepBundle\Flow\FlowHistory::hasDoneStep
     * @covers IDCI\Bundle\StepBundle\Flow\FlowHistory::hasTakenPath
     * @dataProvider getRetraceData
     */
    public function testRetrace(
        $step,
        $path,
        $expectedRemovedStep,
        $expectedRemovedPath,
        $expectedDoneStep,
        $expectedTakenPath
    )
    {
        $flowHistory = self::$flowHistory;

        $this->assertEquals(false, $flowHistory->hasCanceledStep($step));
        $this->assertEquals($expectedRemovedStep, $flowHistory->retraceDoneStep($step));
        $this->assertEquals($expectedRemovedPath, $flowHistory->retraceTakenPath($path));
        $this->assertEquals(true, $flowHistory->hasCanceledStep($step));
        $this->assertEquals(false, $flowHistory->hasDoneStep($step));
        $this->assertEquals(false, $flowHistory->hasTakenPath($step));
        $this->assertEquals(true, $flowHistory->hasDoneStep($expectedDoneStep));
        $this->assertEquals(true, $flowHistory->hasTakenPath($expectedTakenPath));
    }
}