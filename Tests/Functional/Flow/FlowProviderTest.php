<?php

namespace IDCI\Bundle\StepBundle\Tests\Functional\Flow;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class FlowProviderTest extends \PHPUnit_Framework_TestCase
{
    private static $flowProvider;
    private static $session;

    /*public static function setUpBeforeClass()
    {
        require_once __DIR__.'/../../AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();
        self::$flowProvider = $container->get('idci_step.flow.provider');
        self::$session = $container->get('session');
    }*/

    public function getData()
    {
        $data = array();

        # 0
        $data[] = array(
            'plap',
            array(),
            false,
            array('plap')
        );

        # 1
        $data[] = array(
            'plep',
            array(),
            false,
            array('plap', 'plep')
        );

        # 2
        $data[] = array(
            'plip',
            array(),
            false,
            array('plap', 'plep', 'plip')
        );

        # 3
        $data[] = array(
            'plop',
            array(),
            false,
            array('plap', 'plep', 'plip', 'plop')
        );

        # 4
        $data[] = array(
            'plep',
            array(),
            true,
            array('plap', 'plep')
        );

        # 5
        $data[] = array(
            'plup',
            array(),
            false,
            array('plap', 'plep', 'plup')
        );

        return $data;
    }

    /*
     * @covers IDCI\Bundle\StepBundle\Flow\FlowProvider::retrieveFlowDescriptor
     * @covers IDCI\Bundle\StepBundle\Flow\FlowProvider::retrieveDataFlow
     * @covers IDCI\Bundle\StepBundle\Flow\FlowProvider::persistFlowDescriptor
     * @covers IDCI\Bundle\StepBundle\Flow\FlowProvider::persistDataFlow
     * dataProvider getData
     */
    public function testProcess()//$step, array $data, $retrace, array $expectedDoneSteps)
    {
        /*$flowDescriptor = self::$flowProvider->retrieveFlowDescriptor('map');
        $dataFlow = self::$flowProvider->retrieveDataFlow('map');

        if ($retrace) {
            $removedSteps = $flowDescriptor->retraceDoneStep($step);

            foreach ($removedSteps as $removedStep) {
                $dataFlow->unsetStep($removedStep);
            }
        }

        $flowDescriptor->setCurrentStep($step);
        $dataFlow->setStep($step, $data);
        $flowDescriptor->addDoneStep($step);

        $flowDescriptor = self::$flowProvider->persistFlowDescriptor('map', $flowDescriptor);
        $dataFlow = self::$flowProvider->persistDataFlow('map', $dataFlow);

        $stepsDescriptor = json_decode(self::$session->get('idci_step.flow_map_descriptor'), true);
        $this->assertEquals($step, $stepsDescriptor['currentStep']);
        $this->assertEquals($expectedDoneSteps, $stepsDescriptor['doneSteps']);

        $stepsData = json_decode(self::$session->get('idci_step.flow_map_data'), true);
        $steps = $stepsData['steps'];
        $this->assertEquals($data, $steps[$step]);
        $this->assertEquals(count($expectedDoneSteps), count($steps));*/
    }
}