<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use IDCI\Bundle\StepBundle\Navigation\Event\NavigationEventSubscriber;
use IDCI\Bundle\StepBundle\Step\Event\StepEventActionRegistryInterface;
use IDCI\Bundle\StepBundle\Path\Event\PathEventActionRegistryInterface;

class NavigatorType extends AbstractType
{
    /**
     * @var StepEventActionRegistryInterface
     */
    protected $stepEventActionRegistry;

    /**
     * @var PathEventActionRegistryInterface
     */
    protected $pathEventActionRegistry;

    /**
     * @var \Twig_Environment
     */
    protected $merger;

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
     *
     * @param StepEventActionRegistryInterface $stepEventActionRegistry the step event action registry
     * @param PathEventActionRegistryInterface $pathEventActionRegistry the path event action registry
     * @param Twig_Environment                 $merger                  the twig merger
     * @param TokenStorageInterface            $tokenStorage            the security context
     * @param SessionInterface                 $session                 the session
     */
    public function __construct(
        StepEventActionRegistryInterface $stepEventActionRegistry,
        PathEventActionRegistryInterface $pathEventActionRegistry,
        \Twig_Environment                $merger,
        TokenStorageInterface            $tokenStorage,
        SessionInterface                 $session
    ) {
        $this->stepEventActionRegistry = $stepEventActionRegistry;
        $this->pathEventActionRegistry = $pathEventActionRegistry;
        $this->merger = $merger;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $stepOptions = $options['navigator']->getCurrentStep()->getOptions();
        $view->vars = array_merge($view->vars, array(
            'attr' => $stepOptions['attr'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_map_name', HiddenType::class, array(
                'data' => $options['navigator']->getMap()->getName(),
            ))
            ->add('_map_footprint', HiddenType::class, array(
                'data' => $options['navigator']->getMap()->getFootprint(),
            ))
            ->add('_current_step', HiddenType::class, array(
                'data' => $options['navigator']->getCurrentStep()->getName(),
            ))
        ;

        if (null !== $options['navigator']->getPreviousStep()) {
            $builder->add('_previous_step', HiddenType::class, array(
                'data' => $options['navigator']->getPreviousStep()->getName(),
            ));
        }

        if (null !== $options['navigator']->getMap()->getFormAction()) {
            $builder->setAction($options['navigator']->getMap()->getFormAction());
        }

        $this->buildStep($builder, $options);

        $builder->addEventSubscriber(new NavigationEventSubscriber(
            $options['navigator'],
            $this->stepEventActionRegistry,
            $this->pathEventActionRegistry,
            $this->merger,
            $this->tokenStorage,
            $this->session
        ));
    }

    /**
     * Build step.
     *
     * @param FormBuilderInterface $builder the builder
     * @param array                $options the options
     */
    protected function buildStep(FormBuilderInterface $builder, array $options)
    {
        $currentStep = $options['navigator']->getCurrentStep();
        $configuration = $currentStep->getConfiguration();

        $currentStep->getType()->buildNavigationStepForm(
            $builder,
            $configuration['options']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(array('navigator'))
            ->setAllowedTypes('navigator', array('IDCI\Bundle\StepBundle\Navigation\NavigatorInterface'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'idci_step_navigator';
    }
}
