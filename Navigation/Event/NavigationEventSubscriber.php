<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation\Event;

use IDCI\Bundle\StepBundle\Flow\FlowData;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\Event\PathEvent;
use IDCI\Bundle\StepBundle\Path\Event\PathEventActionRegistryInterface;
use IDCI\Bundle\StepBundle\Step\Event\StepEvent;
use IDCI\Bundle\StepBundle\Step\Event\StepEventActionRegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class NavigationEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var NavigatorInterface
     */
    private $navigator;

    /**
     * @var StepEventActionRegistryInterface
     */
    private $stepEventActionRegistry;

    /**
     * @var PathEventActionRegistryInterface
     */
    private $pathEventActionRegistry;

    /**
     * @var Environment
     */
    private $merger;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * Constructor.
     */
    public function __construct(
        NavigatorInterface $navigator,
        StepEventActionRegistryInterface $stepEventActionRegistry,
        PathEventActionRegistryInterface $pathEventActionRegistry,
        Environment $merger,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ) {
        $this->navigator = $navigator;
        $this->stepEventActionRegistry = $stepEventActionRegistry;
        $this->pathEventActionRegistry = $pathEventActionRegistry;
        $this->merger = $merger;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => [
                ['preSetData', 999],
                ['addStepEvents', 2],
                ['addPathEvents', 1],
                ['addNavigationButtons', 0],
            ],
            FormEvents::POST_SET_DATA => [
                ['addStepEvents', 2],
                ['addPathEvents', 1],
            ],
            FormEvents::PRE_SUBMIT => [
                ['checkReturn', 100],
                ['preSubmit', -1],
                ['addStepEvents', -2],
                ['addPathEvents', -3],
            ],
            FormEvents::SUBMIT => [
                ['checkReturn', 100],
                ['addStepEvents', -1],
                ['addPathEvents', -2],
            ],
            FormEvents::POST_SUBMIT => [
                ['checkReturn', 100],
                ['postSubmit', -1],
                ['addStepEvents', -2],
                ['addPathEvents', -3],
            ],
        ];
    }

    /**
     * Add navigation buttons.
     */
    public function addNavigationButtons(FormEvent $event, string $name)
    {
        $form = $event->getForm();
        $map = $this->navigator->getMap();
        $currentStep = $this->navigator->getCurrentStep();
        $stepOptions = $currentStep->getOptions();

        // Add path links as submit input.
        // Do not add path links on steps if this is specified, this allow dynamic changes.
        if (!$stepOptions['prevent_next']) {
            foreach ($this->navigator->getAvailablePaths() as $i => $path) {
                $pathOptions = $path->getOptions();

                $form->add(
                    sprintf('_path_%d', $i),
                    $pathOptions['type'],
                    array_replace_recursive(
                        [
                            'attr' => ['class' => 'idci_step_navigation_next'],
                        ],
                        $pathOptions['next_options']
                    )
                );
            }
        }

        // Add back link as submit input.
        // Do not add back link on steps if this is specified, this allow dynamic changes.
        if ($currentStep->getName() != $map->getFirstStepName() && !$stepOptions['prevent_previous']) {
            $form->add(
                '_back',
                SubmitType::class,
                array_replace_recursive(
                    [
                        'attr' => [
                            'formnovalidate' => 'true',
                            'class' => 'idci_step_navigation_previous',
                        ],
                        'validation_groups' => false,
                    ],
                    $stepOptions['previous_options']
                )
            );
        }
    }

    /**
     * Add step events.
     */
    public function addStepEvents(FormEvent $event, string $name)
    {
        $retrievedData = [];

        $step = $this->navigator->getCurrentStep();
        $options = $step->getOptions();
        $events = $options['events'];

        if (isset($events[$name])) {
            foreach ($events[$name] as $configuration) {
                $resolver = new OptionsResolver();
                $this->configureEventConfiguration($resolver);
                $configuration = $resolver->resolve($configuration);

                $action = $this
                    ->stepEventActionRegistry
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
     */
    public function addPathEvents(FormEvent $event, string $name)
    {
        $form = $event->getForm();
        $retrievedData = [];

        // Prevent triggering path event actions if the form is not valid during post submit event.
        if (FormEvents::POST_SUBMIT === $name && !$form->isValid()) {
            return;
        }

        foreach ($this->navigator->getCurrentPaths() as $i => $path) {
            // Trigger only path event actions on the clicked path during POST_SUBMIT event.
            if (FormEvents::POST_SUBMIT == $name && $this->navigator->getChosenPath() !== $path) {
                continue;
            }

            $options = $path->getOptions();
            $events = $options['events'];

            if (isset($events[$name])) {
                foreach ($events[$name] as $configuration) {
                    $resolver = new OptionsResolver();
                    $this->configurePathEventConfiguration($resolver);
                    $configuration = $resolver->resolve($configuration);

                    // Check the resolved destination match the target destinations for the event
                    // in the case of multi destination paths.
                    if (isset($configuration['destinations'])) {
                        $destination = $path->resolveDestination($this->navigator);

                        if (null === $destination ||
                            !in_array($destination->getName(), $configuration['destinations'])
                        ) {
                            continue;
                        }
                    }

                    $action = $this
                        ->pathEventActionRegistry
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
     * Check return.
     */
    public function checkReturn(FormEvent $event)
    {
        if ($this->navigator->hasReturned()) {
            $event->stopPropagation();
        }
    }

    /**
     * Pre set data.
     */
    public function preSetData(FormEvent $event)
    {
        $data = $this->navigator->getCurrentStepData();
        if ($event->getForm()->has('_content')) {
            $data = ['_content' => $data];
        }

        $event->setData($data);
    }

    /**
     * Pre submit.
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
            $stepOptions = $this->navigator->getCurrentStep()->getOptions();
            if ($stepOptions['prevent_next']) {
                throw new \LogicException(sprintf('A disable path has been clicked'));
            }
        }
    }

    /**
     * Post submit.
     *
     * @return bool
     */
    public function postSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        if (!$form->isValid()) {
            $event->stopPropagation();

            return false;
        }

        foreach ($this->navigator->getAvailablePaths() as $i => $path) {
            if ($form->get(sprintf('_path_%d', $i))->isClicked()) {
                $this->navigator->getFlow()->takePath($path, $i);
                $this->navigator->setChosenPath($path);
            }
        }

        if ($this->navigator->getCurrentStep()->getOptions()['save_content'] &&  $form->has('_content') && $form->isValid()) {
            $this->navigator->setCurrentStepData($form->get('_content')->getData());
        }
    }

    /**
     * Configure event configuration.
     */
    protected function configureEventConfiguration(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['action'])
            ->setDefaults([
                'name' => null,
                'parameters' => [],
            ])
            ->setNormalizer('name', function (Options $options, $value) {
                if (null === $value) {
                    return $options['action'];
                }

                return $value;
            })
            ->setAllowedTypes('action', ['string'])
            ->setAllowedTypes('name', ['null', 'string'])
        ;
    }

    /**
     * Configure path event configuration.
     */
    protected function configurePathEventConfiguration(OptionsResolver $resolver)
    {
        $this->configureEventConfiguration($resolver);

        $resolver
            ->setDefaults([
                'destinations' => null,
            ])
            ->setAllowedTypes('destinations', ['null', 'array'])
        ;
    }

    /**
     * Merge parameters with the SecurityContext (user) the navigation flow data (flow_data) and the session (session).
     */
    public function merge(array $parameters = []): array
    {
        $user = null;
        if (null !== $this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();
        }

        foreach ($parameters as $k => $v) {
            $parameters[$k] = $this->mergeValue($v, [
                'user' => $user,
                'flow_data' => $this->navigator->getFlow()->getData(),
                'session' => $this->session->all(),
            ]);
        }

        return $parameters;
    }

    /**
     * Merge a value.
     *
     * @param mixed $value the value
     * @param array $vars  the merging vars
     *
     * @return mixed the merged value
     */
    private function mergeValue($value, array $vars = [])
    {
        // Handle array case.
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = $this->mergeValue($v, $vars);
            }
        // Handle object case.
        } elseif (is_object($value)) {
            $class = new \ReflectionClass($value);
            $properties = $class->getProperties();

            foreach ($properties as $property) {
                $property->setAccessible(true);

                $property->setValue(
                    $value,
                    $this->mergeValue(
                        $property->getValue($value),
                        $vars
                    )
                );
            }
        // Handle string case.
        } elseif (is_string($value)) {
            $template = $this->merger->createTemplate($value);
            $value = $template->render($vars);
        }

        return $value;
    }
}
