<?php

namespace IDCI\Bundle\StepBundle\Tests\Flow;

use IDCI\Bundle\StepBundle\Flow\FlowDataStoreRegistry;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class FlowDataStoreRegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers IDCI\Bundle\StepBundle\DataStore\SessionDataStore::get
     * @covers IDCI\Bundle\StepBundle\DataStore\SessionDataStore::set
     * @covers IDCI\Bundle\StepBundle\DataStore\SessionDataStore::has
     */
    public function testProcess()
    {
        $dataStoreRegistry = new FlowDataStoreRegistry();

        $dataStore = $this->getMockBuilder('IDCI\Bundle\StepBundle\Flow\DataStore\SessionFlowDataStore')
            ->disableOriginalConstructor()
            ->setMethods(array('get', 'set', 'clear'))
            ->getMock()
        ;

        $this->assertEquals(false, $dataStoreRegistry->hasStore('foo'));
        $dataStoreRegistry->setStore('foo', $dataStore);
        $this->assertEquals(true, $dataStoreRegistry->hasStore('foo'));
        $this->assertEquals(false, $dataStoreRegistry->hasStore('bar'));
        $gotDataStore = $dataStoreRegistry->getStore('foo');
        $this->assertEquals($dataStore, $gotDataStore);
    }
}