<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event;

use Symfony\Component\Form\FormEvent;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

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

    /**
     * @var mixed
     */
    private $pathEventData;

    /**
     * @var integer
     */
    private $pathIndex;

    /**
     * Constructor
     *
     * @param NavigatorInterface $navigator     The navigator.
     * @param FormEvent          $formEvent     The form event.
     * @param mixed              $pathEventData The step event data.
     * @param integer            $pathIndex     The path index.
     */
    public function __construct(
        NavigatorInterface $navigator,
        FormEvent $formEvent,
        $pathEventData,
        $pathIndex
    ) {
        $this->navigator     = $navigator;
        $this->formEvent     = $formEvent;
        $this->pathEventData = $pathEventData;
        $this->pathIndex     = $pathIndex;
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
    public function getName()
    {
        return $this->formEvent->getName();
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
    public function getPathEventData()
    {
        return $this->pathEventData;
    }

    /**
     * {@inheritdoc}
     */
    public function getPathIndex()
    {
        return $this->pathIndex;
    }
}
