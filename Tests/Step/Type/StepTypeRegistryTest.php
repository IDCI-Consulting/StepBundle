<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Tests\Step\Type;

use IDCI\Bundle\StepBundle\Step\Type\AbstractStepType;
use IDCI\Bundle\StepBundle\Step\Type\StepTypeRegistryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\Form;

class StepTypeRegistryTest extends WebTestCase
{
    /**
     * @var StepTypeRegistry
     */
    private $registry;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        require_once __DIR__.'/../../AppKernel.php';
        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();

        $this->registry = $container->get(StepTypeRegistryInterface::class);
    }

    /**
     * Test the getType method.
     */
    public function testGetType()
    {
        $formStepType = $this->registry->getType('form');

        $this->assertInstanceOf(AbstractStepType::class, $formStepType);
    }
}
