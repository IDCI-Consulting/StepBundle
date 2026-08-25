<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 *
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

    public function getName(): string
    {
        return $this->formEvent->getName();
    }

    public function getNavigator(): NavigatorInterface
    {
        return $this->navigator;
    }

    public function getForm(): FormInterface
    {
        return $this->formEvent->getForm();
    }

    public function getData()
    {
        return $this->formEvent->getData();
    }

    public function setData($data)
    {
        $this->formEvent->setData($data);
    }

    public function getStepEventData()
    {
        return $this->stepEventData;
    }

    public function stopPropagation()
    {
        $this->propagationStopped = true;
    }

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}
