<?php

namespace IDCI\Bundle\StepBundle\Tests\Functional\DataStore;

use Symfony\Component\HttpFoundation\Request;
use IDCI\Bundle\StepBundle\Map\Map;
use IDCI\Bundle\StepBundle\Flow\Flow;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class SessionDataStoreTest extends \PHPUnit_Framework_TestCase
{
    private static $container;
    private $dataStore;
    private $request;
    private $map;

    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/../../AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        self::$container = $kernel->getContainer();
    }

    public function setUp()
    {
        $this->dataStore = self::$container->get('idci_step.flow.data_store.session');
        $session = self::$container->get('session');
        $this->request = new Request();
        $this->request->setSession($session);
        $this->map = new Map(array(
            'name' => 'foo',
            'finger_print' => 'abc123'
        ));
    }

    public function getData()
    {
        $data = array();

        # 0
        $data[] = array(new Flow());

        # 1
        $flow = new Flow();
        $flowData = $flow->getData();
        $flowData->setStepData('plop', array('foo' => 'bar'));
        $data[] = array($flow);

        # 2
        $flow = new Flow();
        $flowData = $flow->getData();
        $flowData->setStepData('plop', array('foo' => 'bar'));
        $flowData->setStepData('plip', array('bar' => 2));
        $data[] = array($flow);

        # 3
        $flow = new Flow();
        $flowData = $flow->getData();
        $flowData->setStepData('plop', array('foo' => new \DateTime()));
        $flowData->setStepData('plip', array('bar' => 2));
        $data[] = array($flow);

        return $data;
    }

    /**
     * @covers IDCI\Bundle\StepBundle\DataStore\SessionDataStore::get
     * @covers IDCI\Bundle\StepBundle\DataStore\SessionDataStore::set
     * @covers IDCI\Bundle\StepBundle\DataStore\SessionDataStore::clear
     * @dataProvider getData
     */
    public function testProcess($flow)
    {
        $this->dataStore->set($this->map, $this->request, $flow);
        $retrievedFlow = $this->dataStore->get($this->map, $this->request);

        $this->assertEquals(
            $flow,
            $retrievedFlow
        );

        $this->dataStore->clear($this->map, $this->request);
        $retrievedFlow = $this->dataStore->get($this->map, $this->request);

        $this->assertEquals(
            null,
            $retrievedFlow
        );
    }
}