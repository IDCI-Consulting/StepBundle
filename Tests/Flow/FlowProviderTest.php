<?php

namespace IDCI\Bundle\StepBundle\Flow;

/**
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class FlowProviderTest extends \PHPUnit_Framework_TestCase
{
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
        $this->assertEquals(1, $plop);
    }
}