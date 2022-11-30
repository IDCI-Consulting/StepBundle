<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;

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
     * Constructor.
     */
    public function __construct(NavigatorInterface $navigator, FormEvent $formEvent, $stepEventData)
    {
        $this->navigator = $navigator;
        $this->formEvent = $formEvent;
        $this->stepEventData = $stepEventData;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->formEvent->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getNavigator(): NavigatorInterface
    {
        return $this->navigator;
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(): FormInterface
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
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}
