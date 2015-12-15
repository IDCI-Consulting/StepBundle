<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation\Event;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Step\Event\StepEventRegistryInterface;
use IDCI\Bundle\StepBundle\Step\Event\StepEvent;
use IDCI\Bundle\StepBundle\Path\Event\PathEventRegistryInterface;
use IDCI\Bundle\StepBundle\Path\Event\PathEvent;
use IDCI\Bundle\StepBundle\Flow\FlowData;

class NavigationEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var NavigatorInterface
     */
    private $navigator;

    /**
     * @var StepEventRegistryInterface
     */
    private $stepEventRegistry;

    /**
     * @var PathEventRegistryInterface
     */
    private $pathEventRegistry;

    /**
     * @var \Twig_Environment
     */
    private $merger;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * Constructor
     *
     * @param NavigatorInterface         $navigator         The navigator.
     * @param StepEventRegistryInterface $stepEventRegistry The step event registry.
     * @param PathEventRegistryInterface $pathEventRegistry The path event registry.
     * @param \Twig_Environment          $merger            The merger.
     * @param SecurityContextInterface   $securityContext   The security context.
     * @param SessionInterface           $session           The session.
     */
    public function __construct(
        NavigatorInterface $navigator,
        StepEventRegistryInterface $stepEventRegistry,
        PathEventRegistryInterface $pathEventRegistry,
        \Twig_Environment          $merger,
        SecurityContextInterface   $securityContext,
        SessionInterface           $session
    )
    {
        $this->navigator         = $navigator;
        $this->stepEventRegistry = $stepEventRegistry;
        $this->pathEventRegistry = $pathEventRegistry;
        $this->merger            = $merger;
        $this->securityContext   = $securityContext;
        $this->session           = $session;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => array(
                array('preSetData', 999),
                array('addNavigationButtons', 100),
                array('addStepEvents', 2),
                array('addPathEvents', 1),
            ),
            FormEvents::POST_SET_DATA => array(
                array('addStepEvents', 2),
                array('addPathEvents', 1),
            ),
            FormEvents::PRE_SUBMIT    => array(
                array('preSubmit', 0),
                array('addStepEvents', -1),
                array('addPathEvents', -2),
            ),
            FormEvents::SUBMIT        => array(
                array('addStepEvents', -1),
                array('addPathEvents', -2),
            ),
            FormEvents::POST_SUBMIT   => array(
                array('postSubmit', 0),
                array('addStepEvents', -1),
                array('addPathEvents', -2),
            ),
        );
    }

    /**
     * Add navigation buttons.
     *
     * @param FormEvent $event
     */
    public function addNavigationButtons(FormEvent $event)
    {
        $form              = $event->getForm();
        $map               = $this->navigator->getMap();
        $currentStep       = $this->navigator->getCurrentStep();
        $stepConfiguration = $currentStep->getConfiguration();

        if (
            $currentStep->getName() !== $map->getFirstStepName() &&
            !$stepConfiguration['options']['prevent_previous']
        ) {
            $form->add(
                '_back',
                'submit',
                array_merge_recursive(
                    $stepConfiguration['options']['previous_options'],
                    array('attr' => array('formnovalidate' => 'true'))
                )
            );
        }

        // Do not add path link on steps if this is specified, this allow dynamic changes
        if ($stepConfiguration['options']['prevent_next']) {
            return;
        }

        foreach ($this->navigator->getAvailablePaths() as $i => $path) {
            $pathConfiguration = $path->getConfiguration();

            $form->add(
                sprintf('_path_%d', $i),
                $pathConfiguration['options']['type'],
                $pathConfiguration['options']['next_options']
            );
        }
    }

    /**
     * Add step events.
     *
     * @param FormEvent $event
     */
    public function addStepEvents(FormEvent $event)
    {
        $retrievedData = array();

        $step = $this->navigator->getCurrentStep();
        $configuration = $step->getConfiguration();
        $events = $configuration['options']['events'];

        if (isset($events[$event->getName()])) {
            foreach ($events[$event->getName()] as $configuration) {
                $resolver = new OptionsResolver();
                $this->configureEventConfiguration($resolver);
                $configuration = $resolver->resolve($configuration);

                $action = $this
                    ->stepEventRegistry
                    ->getAction($configuration['action'])
                ;

                $retrievedData = $this->navigator->getCurrentStepData(FlowData::TYPE_RETRIEVED);
                $stepEventData = isset($retrievedData[$configuration['name']]) ?
                    $retrievedData[$configuration['name']] :
                    null
                ;

                $stepEvent = new StepEvent($this->navigator, $event, $stepEventData);
                // TODO: Catch execution exceptions and handle them (clear navigator, log, stop propagation, ...)
                $result = $action->execute(
                    $stepEvent,
                    $this->merge($configuration['parameters'])
                );

                if (null !== $result) {
                    $retrievedData[$configuration['name']] = $result;

                    $this->navigator->setCurrentStepData(
                        $retrievedData,
                        FlowData::TYPE_RETRIEVED
                    );
                }

                if ($stepEvent->isPropagationStopped()) {
                    break;
                }
            }
        }
    }

    /**
     * Add path events.
     *
     * @param FormEvent $event
     */
    public function addPathEvents(FormEvent $event)
    {
        $form          = $event->getForm();
        $retrievedData = array();

        // Prevent triggering path event actions if the form is not valid during post submit event.
        if (FormEvents::POST_SUBMIT === $event->getName() && !$form->isValid()) {
            return;
        }

        foreach ($this->navigator->getCurrentPaths() as $i => $path) {
            // Trigger only path event actions on the clicked path during POST_SUBMIT event.
            if ($event->getName() == FormEvents::POST_SUBMIT && (
                $this->navigator->hasReturned() ||
                $this->navigator->getChosenPath() !== $path
            )) {
                continue;
            }

            $configuration = $path->getConfiguration();
            $events = $configuration['options']['events'];

            if (isset($events[$event->getName()])) {
                foreach ($events[$event->getName()] as $configuration) {
                    $resolver = new OptionsResolver();
                    $this->configurePathEventConfiguration($resolver);
                    $configuration = $resolver->resolve($configuration);

                    // Check the resolved destination match the target destinations for the event
                    // in the case of multi destination paths.
                    if (isset($configuration['destinations'])) {
                        $destination = $path->resolveDestination($this->navigator);

                        if (
                            null === $destination ||
                            !in_array($destination->getName(), $configuration['destinations'])
                        ) {
                            continue;
                        }
                    }

                    $action = $this
                        ->pathEventRegistry
                        ->getAction($configuration['action'])
                    ;

                    $retrievedData = $this->navigator->getCurrentStepData(FlowData::TYPE_RETRIEVED);
                    $pathEventData = isset($retrievedData[$configuration['name']]) ?
                        $retrievedData[$configuration['name']] :
                        null
                    ;

                    $pathEvent = new PathEvent($this->navigator, $event, $pathEventData, $i);
                    // TODO: Catch execution exceptions and handle them (clear navigator, log, stop propagation, ...)
                    $result = $action->execute(
                        $pathEvent,
                        $this->merge($configuration['parameters'])
                    );

                    if (null !== $result) {
                        $retrievedData[$configuration['name']] = $result;

                        $this->navigator->setCurrentStepData(
                            $retrievedData,
                            FlowData::TYPE_RETRIEVED
                        );
                    }

                    if ($pathEvent->isPropagationStopped()) {
                        break;
                    }
                }
            }
        }
    }

    /**
     * Pre set data.
     *
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $data = $this->navigator->getCurrentStepData();
        if ($event->getForm()->has('_data')) {
            $data = array('_data' => $data);
        }

        $event->setData($data);
    }

    /**
     * Pre submit.
     *
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();

        if (isset($data['_back'])) {
            if (empty($data['_back'])) {
                $this->navigator->goBack();
            } else {
                $this->navigator->goBack($data['_back']);
            }
        } else {
            $stepConfiguration = $this->navigator->getCurrentStep()->getConfiguration();
            if ($stepConfiguration['options']['prevent_next']) {
                throw new \LogicException(sprintf(
                    'A disable path has been clicked'
                ));
            }
        }
    }

    /**
     * Post submit.
     *
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
        if ($this->navigator->hasReturned()) {
            return;
        }

        $form = $event->getForm();

        foreach ($this->navigator->getAvailablePaths() as $i => $path) {
            if ($form->get(sprintf('_path_%d', $i))->isClicked()) {
                $this->navigator->getFlow()->takePath($path, $i);
                $this->navigator->setChosenPath($path);
            }
        }

        if ($form->has('_data') && $form->isValid()) {
            $this->navigator->setCurrentStepData($form->get('_data')->getData());
        }
    }

    /**
     * Configure event configuration.
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function configureEventConfiguration(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('action'))
            ->setDefaults(array(
                'name'       => null,
                'parameters' => array(),
            ))
            ->setNormalizers(array(
                'name' => function (Options $options, $value) {
                    if (null === $value) {
                        return $options['action'];
                    }

                    return $value;
                }
            ))
            ->setAllowedTypes(array(
                'action' => array('string'),
                'name'   => array('null', 'string'),
            ))
        ;
    }

    /**
     * Configure path event configuration.
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function configurePathEventConfiguration(OptionsResolverInterface $resolver)
    {
        $this->configureEventConfiguration($resolver);

        $resolver
            ->setDefaults(array(
                'destinations' => null,
            ))
            ->setAllowedTypes(array(
                'destinations' => array('null', 'array'),
            ))
        ;
    }

    /**
     * Merge parameters with the SecurityContext (user)
     * and the navigation flow data (flow_data).
     *
     * @param array $parameters The parameters.
     *
     * @return array
     */
    protected function merge(array $parameters = array())
    {
        $user = null;
        if (null !== $this->securityContext->getToken()) {
            $user = $this->securityContext->getToken()->getUser();
        }

        foreach ($parameters as $k => $v) {
            $parameters[$k] = json_decode(
                $this->merger->render(
                    json_encode($v, JSON_UNESCAPED_UNICODE),
                    array(
                        'user'      => $user,
                        'flow_data' => $this->navigator->getFlow()->getData(),
                        'session'   => $this->session,
                    )
                ),
                true
            );
        }

        return $parameters;
    }
}
