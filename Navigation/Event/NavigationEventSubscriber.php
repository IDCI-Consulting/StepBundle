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
use IDCI\Bundle\StepBundle\Path\Event\PathEventRegistryInterface;

class NavigationEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var NavigatorInterface
     */
    protected $navigator;

    /**
     * @var PathEventRegistryInterface
     */
    protected $pathEventRegistry;

    /**
     * Constructor
     *
     * @param NavigatorInterface         $navigator         The navigator.
     * @param PathEventRegistryInterface $pathEventRegistry The path event registry.
     */
    public function __construct(NavigatorInterface $navigator, PathEventRegistryInterface $pathEventRegistry)
    {
        $this->navigator         = $navigator;
        $this->pathEventRegistry = $pathEventRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => array(array('addPathEvents', 10)),
            FormEvents::POST_SET_DATA => array(array('addPathEvents', 10)),
            FormEvents::PRE_SUBMIT    => array(
                array('preSubmit', 0),
                array('addPathEvents', 10)
            ),
            FormEvents::SUBMIT        => array(array('addPathEvents', 10)),
            FormEvents::POST_SUBMIT   => array(array('addPathEvents', 10)),
        );
    }

    /**
     * Add path events.
     *
     * @param FormEvent $event
     */
    public function addPathEvents(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        foreach ($this->navigator->getCurrentPaths() as $i => $path) {
            $configuration = $path->getConfiguration();
            $events = $configuration['options']['events'];

            if (isset($events[$event->getName()])) {
                foreach ($events[$event->getName()] as $configuration) {
                    $action = $this
                        ->pathEventRegistry
                        ->getAction($configuration['action'])
                    ;

                    $action->execute(
                        $form,
                        $this->navigator,
                        $configuration['parameters']
                    );
                }
            }
        }
    }

    /**
     * Pre submit.
     *
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (isset($data['_data']) && !isset($data['_back'])) {
            $this->navigator->setCurrentStepData($data['_data']);
        }
    }
}
