<?php

namespace IDCI\Bundle\StepBundle\Tests\Functional\Flow;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class FlowProviderTest extends WebTestCase
{
    private $container;

    protected function setUp()
    {
        require_once __DIR__.'/../../AppKernel.php';

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
     * @covers IDCI\Bundle\StepBundle\Flow\FlowProvider::initialize
     * @covers IDCI\Bundle\StepBundle\Flow\FlowProvider::save
     * @dataProvider getData
     */
    public function testProcess($plop, $plip)
    {
        $flowProvider = $this->container->get('idci_step.flow.provider');
        $session = $this->container->get('session');

        $data = json_decode($session->get('idci_step.flow_data'), true);
        $this->assertEquals('2', $data['id']);
    }
}