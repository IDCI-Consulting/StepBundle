<?php

namespace IDCI\Bundle\StepBundle\Tests\Functional\DataStore;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class SessionDataStoreTest extends WebTestCase
{
    private static $dataStore;

    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/../../AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();
        self::$dataStore = $container->get('idci_step.data_store.session');
    }

    public function getData()
    {
        $data = array();

        # 0
        $data[] = array(
            'map',
            'plop',
            1,
            1
        );

        # 1
        $data[] = array(
            'map',
            'plip',
            array('foo' => 'bar'),
            array('foo' => 'bar')
        );

        # 2
        $data[] = array(
            'map',
            'plap',
            3,
            3
        );

        # 3
        $data[] = array(
            'map',
            null,
            null,
            array(
                'plop' => 1,
                'plip' => array('foo' => 'bar'),
                'plap' => 3
            )
        );

        # 4
        $data[] = array(
            'map',
            'plop',
            null,
            null
        );

        # 5
        $data[] = array(
            'map',
            null,
            null,
            array(
                'plip' => array('foo' => 'bar'),
                'plap' => 3
            )
        );

        # 6
        $data[] = array(
            'mop',
            null,
            null,
            array()
        );

        # 7
        $data[] = array(
            'map',
            null,
            false,
            array()
        );

        # 8
        $data[] = array(
            'map',
            'plop',
            1,
            1
        );

        # 9
        $data[] = array(
            'mop',
            'plop',
            2,
            2
        );

        # 10
        $data[] = array(
            'mop',
            null,
            null,
            array('plop' => 2)
        );

        # 11
        $data[] = array(
            'map',
            null,
            null,
            array('plop' => 1)
        );

        return $data;
    }

    /**
     * @covers IDCI\Bundle\StepBundle\DataStore\SessionDataStore::get
     * @covers IDCI\Bundle\StepBundle\DataStore\SessionDataStore::set
     * @covers IDCI\Bundle\StepBundle\DataStore\SessionDataStore::clear
     * @dataProvider getData
     */
    public function testProcess($namespace, $key, $data, $expected)
    {
        if ($key) {
            self::$dataStore->set($namespace, $key, $data);

            $this->assertEquals(
                $expected,
                self::$dataStore->get($namespace, $key)
            );
        } else {
            if (false === $data) {
                self::$dataStore->clear($namespace, $key, $data);
            }

            $this->assertEquals(
                $expected,
                self::$dataStore->get($namespace)
            );
        }
    }
}