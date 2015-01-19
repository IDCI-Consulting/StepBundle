<?php

namespace IDCI\Bundle\StepBundle\Tests\Functional\Navigation;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class ConfigurationFetchedNavigationTest extends AbstractNavigationTest
{
    public function setUp()
    {
        $this->map = 'config';
        $this->options = array('name' => 'test');
    }
}