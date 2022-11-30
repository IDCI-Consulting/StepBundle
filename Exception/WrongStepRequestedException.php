<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Exception;

class WrongStepRequestedException extends \LogicException
{
    protected $redirectionStep;

    public function __construct($given, $expected)
    {
        parent::__construct(sprintf('Cannot access step "%s" before step "%s"', $given, $expected));

        $this->redirectionStep = $expected;
    }

    public function getRedirectionStep()
    {
        return $this->redirectionStep;
    }
}
