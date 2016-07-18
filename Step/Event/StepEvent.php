<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event;

use Symfony\Component\Form\FormEvent;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class StepEvent implements StepEventInterface
{
    /**
     * @var bool Whether no further event should be triggered
     */
    private $propagationStopped = false;

    /**
     * @var NavigatorInterface
     */
    private $navigator;

    /**
     * @var FormEvent
     */
    private $formEvent;

    /**
     * @var mixed
     */
    private $stepEventData;

    /**
     * Constructor
     *
     * @param NavigatorInterface $navigator     The navigator.
     * @param FormEvent          $formEvent     The form event.
     * @param mixed              $stepEventData the step event data.
     */
    public function __construct(
        NavigatorInterface $navigator,
        FormEvent $formEvent,
        $stepEventData
    )
    {
        $this->navigator     = $navigator;
        $this->formEvent     = $formEvent;
        $this->stepEventData = $stepEventData;
    }

    /**
     * {@inheritdoc}
     */
    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }

    /**
     * {@inheritdoc}
     */
    public function stopPropagation()
    {
        $this->propagationStopped = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getNavigator()
    {
        return $this->navigator;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->formEvent->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        return $this->formEvent->getForm();
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->formEvent->getData();
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->formEvent->setData($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getStepEventData()
    {
        return $this->stepEventData;
    }
}
