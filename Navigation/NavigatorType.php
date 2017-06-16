<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
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
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * Constructor
     *
     * @param StepEventActionRegistryInterface $stepEventActionRegistry The step event action registry.
     * @param PathEventActionRegistryInterface $pathEventActionRegistry The path event action registry.
     * @param Twig_Environment                 $merger                  The twig merger.
     * @param SecurityContextInterface         $securityContext         The security context.
     * @param SessionInterface                 $session                 The session.
     */
    public function __construct(
        StepEventActionRegistryInterface $stepEventActionRegistry,
        PathEventActionRegistryInterface $pathEventActionRegistry,
        \Twig_Environment                $merger,
        SecurityContextInterface         $securityContext,
        SessionInterface                 $session
    ) {
        $this->stepEventActionRegistry = $stepEventActionRegistry;
        $this->pathEventActionRegistry = $pathEventActionRegistry;
        $this->merger                  = $merger;
        $this->securityContext         = $securityContext;
        $this->session                 = $session;
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
            ->add('_map_name', 'hidden', array(
                'data' => $options['navigator']->getMap()->getName()
            ))
            ->add('_map_footprint', 'hidden', array(
                'data' => $options['navigator']->getMap()->getFootprint()
            ))
            ->add('_current_step', 'hidden', array(
                'data' => $options['navigator']->getCurrentStep()->getName()
            ))
        ;

        if (null !== $options['navigator']->getPreviousStep()) {
            $builder->add('_previous_step', 'hidden', array(
                'data' => $options['navigator']->getPreviousStep()->getName()
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
            $this->securityContext,
            $this->session
        ));
    }

    /**
     * Build step.
     *
     * @param FormBuilderInterface $builder The builder.
     * @param array                $options The options.
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
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('navigator'))
            ->setAllowedTypes(array(
                'navigator'=> array('IDCI\Bundle\StepBundle\Navigation\NavigatorInterface')
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idci_step_navigator';
    }
}
