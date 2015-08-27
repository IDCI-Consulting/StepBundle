<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Navigation\Event\NavigationEventSubscriber;
use IDCI\Bundle\StepBundle\Step\Event\StepEventRegistryInterface;
use IDCI\Bundle\StepBundle\Path\Event\PathEventRegistryInterface;

class NavigatorType extends AbstractType
{
    /**
     * @var StepEventRegistryInterface
     */
    protected $stepEventRegistry;

    /**
     * @var PathEventRegistryInterface
     */
    protected $pathEventRegistry;

    /**
     * @var \Twig_Environment
     */
    protected $merger;

    /**
     * Constructor
     *
     * @param StepEventRegistryInterface $stepEventRegistry The step event registry.
     * @param PathEventRegistryInterface $pathEventRegistry The path event registry.
     * @param Twig_Environment           $merger            The twig merger.
     */
    public function __construct(
        StepEventRegistryInterface $stepEventRegistry,
        PathEventRegistryInterface $pathEventRegistry,
        \Twig_Environment          $merger
    )
    {
        $this->stepEventRegistry = $stepEventRegistry;
        $this->pathEventRegistry = $pathEventRegistry;
        $this->merger            = $merger;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_map_name', 'hidden', array(
                'data' => $options['navigator']->getMap()->getName())
            )
            ->add('_map_finger_print', 'hidden', array(
                'data' => $options['navigator']->getMap()->getFingerPrint())
            )
            ->add('_current_step', 'hidden', array(
                'data' => $options['navigator']->getCurrentStep()->getName())
            )
        ;

        if (null !== $options['navigator']->getPreviousStep()) {
            $builder->add('_previous_step', 'hidden', array(
                'data' => $options['navigator']->getPreviousStep()->getName()
            ));
        }

        $this->buildStep($builder, $options);

        $builder->addEventSubscriber(new NavigationEventSubscriber(
            $options['navigator'],
            $this->stepEventRegistry,
            $this->pathEventRegistry,
            $this->merger
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
        $navigator = $options['navigator'];
        $currentStep = $navigator->getCurrentStep();
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