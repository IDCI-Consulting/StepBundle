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
use IDCI\Bundle\StepBundle\Path\Event\PathEventRegistryInterface;
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
     * Constructor
     *
     * @param NavigatorInterface         $navigator         The navigator.
     * @param StepEventRegistryInterface $stepEventRegistry The step event registry.
     * @param PathEventRegistryInterface $pathEventRegistry The path event registry.
     * @param \Twig_Environment          $merger            The merger.
     * @param SecurityContextInterface   $securityContext   The security context.
     */
    public function __construct(
        NavigatorInterface $navigator,
        StepEventRegistryInterface $stepEventRegistry,
        PathEventRegistryInterface $pathEventRegistry,
        \Twig_Environment          $merger,
        SecurityContextInterface   $securityContext
    )
    {
        $this->navigator         = $navigator;
        $this->stepEventRegistry = $stepEventRegistry;
        $this->pathEventRegistry = $pathEventRegistry;
        $this->merger            = $merger;
        $this->securityContext   = $securityContext;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => array(
                array('addStepEvents', 2),
                array('addPathEvents', 1),
            ),
            FormEvents::POST_SET_DATA => array(
                array('addStepEvents', 2),
                array('addPathEvents', 1),
                array('postSetData', 0),
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
     * Add step events.
     *
     * @param FormEvent $event
     */
    public function addStepEvents(FormEvent $event)
    {
        $form          = $event->getForm();
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
                $data = isset($retrievedData[$configuration['name']]) ?
                    $retrievedData[$configuration['name']] :
                    null
                ;

                $result = $action->execute(
                    $form,
                    $this->navigator,
                    $this->merge($configuration['parameters']),
                    $data
                );

                if (null !== $result) {
                    $retrievedData[$configuration['name']] = $result;

                    $this->navigator->setCurrentStepData(
                        $retrievedData,
                        array(),
                        FlowData::TYPE_RETRIEVED
                    );
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
            // Trigger only path event actions on the clicked path during submit events.
            if (
                in_array($event->getName(), array(
                    FormEvents::PRE_SUBMIT,
                    FormEvents::SUBMIT,
                    FormEvents::POST_SUBMIT
                )) &&
                ($this->navigator->hasReturned() || !$form->get('_path_'.$i)->isClicked())
            ) {
                continue;
            }

            $configuration = $path->getConfiguration();
            $events = $configuration['options']['events'];

            if (isset($events[$event->getName()])) {
                foreach ($events[$event->getName()] as $configuration) {
                    $resolver = new OptionsResolver();
                    $this->configureEventConfiguration($resolver);
                    $configuration = $resolver->resolve($configuration);

                    $action = $this
                        ->pathEventRegistry
                        ->getAction($configuration['action'])
                    ;

                    $retrievedData = $this->navigator->getCurrentStepData(FlowData::TYPE_RETRIEVED);
                    $data = isset($retrievedData[$configuration['name']]) ?
                        $retrievedData[$configuration['name']] :
                        null
                    ;

                    $result = $action->execute(
                        $form,
                        $this->navigator,
                        $i,
                        $this->merge($configuration['parameters']),
                        $data
                    );

                    if (null !== $result) {
                        $retrievedData[$configuration['name']] = $result;

                        $this->navigator->setCurrentStepData(
                            $retrievedData,
                            array(),
                            FlowData::TYPE_RETRIEVED
                        );
                    }
                }
            }
        }
    }

    /**
     * Post set data.
     *
     * @param FormEvent $event
     */
    public function postSetData(FormEvent $event)
    {
        $this->navigator->save();
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
        }
    }

    /**
     * Post submit.
     *
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if ($this->navigator->hasReturned()) {
            return;
        }

        if (isset($data['_data']) && $form->isValid()) {
            $this->navigator->setCurrentStepData(
                $data['_data'],
                $this->buildDataFormTypeMapping($form->get('_data'))
            );
        }
    }

    /**
     * Build data form type mapping.
     *
     * @param FormInterface $form The form.
     *
     * @return array
     */
    protected function buildDataFormTypeMapping(FormInterface $form)
    {
        $mapping = array();
        foreach($form->all() as $key => $field) {
            $mapping[$key] = $field->getConfig()->getType()->getName();
        }

        return $mapping;
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
                    json_encode($v),
                    array(
                        'flow_data' => $this->navigator->getFlow()->getData(),
                        'user'      => $user
                    )
                ),
                true
            );
        }

        return $parameters;
    }
}
