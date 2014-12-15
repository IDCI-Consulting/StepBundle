<?php

namespace IDCI\Bundle\StepBundle\Flow;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class FlowProviderTest extends WebTestCase
{
    private $container;

    protected function setUp()
    {
        require_once __DIR__.'/../AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $kernel->loadClassCache();
        $this->container = $kernel->getContainer();
    }

    public function getData()
    {
        $data = array();

        # 1
        $data[] = array(
            1, 2
        );

        return $data;
    }

    /**
     * @covers IDCI\Bundle\StepBundle\Flow\FlowProvider::process
     * @dataProvider getData
     */
    public function testProcess($plop, $plip)
    {
        $flowProvider = $this->container->get('idci_step.flow.provider');
        $this->assertEquals(1, $plop);
    }
}