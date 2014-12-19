<?php

namespace IDCI\Bundle\StepBundle\Tests\Functional\Flow;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class NavigationTest extends WebTestCase
{
    private static $map;

    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/../AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();
        $mapBuilderFactory = $container->get('idci_step.map.builder.factory');
        $mapBuilder = $mapBuilderFactory->createBuilder();

        self::$map = $mapBuilder
            ->addStep('plap', 'content', array())
            ->addStep('plep', 'content', array())
            ->addStep('plip', 'content', array())
            ->addStep('plop', 'content', array())
            ->addStep('plup', 'content', array())
            ->addPath('single', array('source' => 'plap', 'destination' => 'plep'))
            ->getMap()
        ;
    }

    public function getData()
    {
        $data = array();

        # 0
        $data[] = array(
            'plep',
            array(),
            '?'
        );

        return $data;
    }

    /**
     * @dataProvider getData
     */
    public function testNavigate($destination, array $data = null, $expected)
    {
        //$this->assertEqual($expected, self::$map->navigate($destination, $data))
    }
}