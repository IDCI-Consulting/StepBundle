<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Exception;

class InvalidDestinationException extends \LogicException
{
    public function __construct($source, $destination)
    {
        parent::__construct(sprintf('No destination "%s" found for source "%s"', $destination, $source));
    }
}
