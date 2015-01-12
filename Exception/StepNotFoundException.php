<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Exception;

class StepNotFoundException extends \LogicException
{
    public function __construct($stepName, $mapName)
    {
        parent::__construct(sprintf(
            'No step "%s" found in the map "%s"',
            $stepName,
            $mapName
        ));
    }
}