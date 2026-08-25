<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 *
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;

class PathEvent implements PathEventInterface
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

    private $pathEventData;

    /**
     * @var int
     */
    private $pathIndex;

    /**
     * Constructor.
     *
     * @param NavigatorInterface $navigator     the navigator
     * @param FormEvent          $formEvent     the form event
     * @param mixed              $pathEventData the step event data
     * @param int                $pathIndex     the path index
     */
    public function __construct(
        NavigatorInterface $navigator,
        FormEvent $formEvent,
        $pathEventData,
        int $pathIndex,
    ) {
        $this->navigator = $navigator;
        $this->formEvent = $formEvent;
        $this->pathEventData = $pathEventData;
        $this->pathIndex = $pathIndex;
    }

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    public function stopPropagation()
    {
        $this->propagationStopped = true;
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

    public function getPathEventData()
    {
        return $this->pathEventData;
    }

    public function getPathIndex(): int
    {
        return $this->pathIndex;
    }
}
