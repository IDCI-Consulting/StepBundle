<?php

namespace IDCI\Bundle\StepBundle\Tests\DataStore;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use IDCI\Bundle\StepBundle\DataStore\DataStoreRegistry;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class DataStoreRegistryTest extends WebTestCase
{
    /**
     * @covers IDCI\Bundle\StepBundle\DataStore\SessionDataStore::get
     * @covers IDCI\Bundle\StepBundle\DataStore\SessionDataStore::set
     * @covers IDCI\Bundle\StepBundle\DataStore\SessionDataStore::has
     */
    public function testProcess()
    {
        $dataStoreRegistry = new DataStoreRegistry();

        $dataStore = $this->getMockBuilder('IDCI\Bundle\StepBundle\DataStore\SessionDataStore')
            ->disableOriginalConstructor()
            ->setMethods(array('get', 'set', 'clear'))
            ->getMock()
        ;

        $this->assertEquals(false, $dataStoreRegistry->has('foo'));
        $dataStoreRegistry->set('foo', $dataStore);
        $this->assertEquals(true, $dataStoreRegistry->has('foo'));
        $this->assertEquals(false, $dataStoreRegistry->has('bar'));
        $gotDataStore = $dataStoreRegistry->get('foo');
        $this->assertEquals($dataStore, $gotDataStore);
    }
}