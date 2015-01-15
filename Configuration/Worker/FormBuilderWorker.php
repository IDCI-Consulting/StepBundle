<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Worker;

use Symfony\Component\Form\FormFactoryInterface;

class FormBuilderWorker implements ConfigurationWorkerInterface
{
    /**
     * The form factory.
     *
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * Constructor.
     *
     * @param FormFactoryInterface $formFactory The form factory.
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function work(array $parameters = array())
    {
        // TODO: Implement.
    }
}