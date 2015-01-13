<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation\Event;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class NavigationEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var NavigatorInterface
     */
    protected $navigator;

    /**
     * Constructor
     */
    public function __construct(NavigatorInterface $navigator)
    {
        $this->navigator = $navigator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT   => 'preSubmit',
            FormEvents::POST_SUBMIT  => 'postSubmit'
        );
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (isset($data['_data']) && !isset($data['_back'])) {
            $this->navigator->setCurrentStepData($data['_data']);
        }
    }

    public function postSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        /*
        $map = $this->navigator->getMap();
        $flow = $this->navigator->getFlow();

        $destinationStep = null;

        if ($form->has('_back') && $form->get('_back')->isClicked()) {
            $previousStep = $map->getStep($flow->getPreviousStepName());
            $flow->retraceTo($previousStep);

            $destinationStep = $previousStep;
        } else {
            $path = $this->getChosenPath($form);
            $destinationStep = $path->resolveDestination($this->navigator);

            if (null === $destinationStep) {
                $this->navigator->hasFinished = true;
            }
        }

        if (null !== $destinationStep) {
            $this->navigator->hasNavigated = true;
            $flow->setCurrentStep($destinationStep);
        }

        return $destinationStep;
        */
    }
}
