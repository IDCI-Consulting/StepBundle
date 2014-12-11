<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step;

use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Step\View\StepView;

class Step implements StepInterface
{
    /**
     * The type of the step.
     *
     * @var string
     */
    protected $type;

    /**
     * The options.
     *
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param StepInterface $type    The type of the step.
     * @param array         $options The options.
     */
    public function __construct($type, array $options = array())
    {
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function createView()
    {
        return new StepView();
    }
}
