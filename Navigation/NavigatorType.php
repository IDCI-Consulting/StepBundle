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
use IDCI\Bundle\StepBundle\Path\Event\PathEventRegistryInterface;

class NavigatorType extends AbstractType
{
    /**
     * @var PathEventRegistryInterface
     */
    protected $pathEventRegistry;

    /**
     * Constructor
     *
     * @param PathEventRegistryInterface $pathEventRegistry The path event registry.
     */
    public function __construct(PathEventRegistryInterface $pathEventRegistry)
    {
        $this->pathEventRegistry = $pathEventRegistry;
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

        $builder->addEventSubscriber(new NavigationEventSubscriber(
            $options['navigator'],
            $this->pathEventRegistry
        ));

        $this->buildStep($builder, $options);
        $this->buildSubmit($builder, $options);
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

        if (!empty($options['data'])) {
            $configuration['options']['data'] = $options['data'];
        }

        $currentStep->getType()->buildNavigationStepForm(
            $builder,
            $configuration['options']
        );
    }

    /**
     * Build submit buttons.
     *
     * @param FormBuilderInterface $builder The builder.
     * @param array                $options The options.
     */
    protected function buildSubmit(FormBuilderInterface $builder, array $options)
    {
        $map = $options['navigator']->getMap();
        $currentStep = $options['navigator']->getCurrentStep();
        $configuration = $currentStep->getConfiguration();

        if ($currentStep->getName() !== $map->getFirstStepName()) {
            $builder->add(
                '_back',
                'submit',
                array_merge(
                    $configuration['options']['previous_options'],
                    array('attr' => array('formnovalidate' => 'true'))
                )
            );
        }

        foreach ($options['navigator']->getAvailablePaths() as $i => $path) {
            $configuration = $path->getConfiguration();

            $builder->add(
                sprintf('_path#%d', $i),
                'submit',
                $configuration['options']['next_options']
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('navigator'))
            ->setDefaults(array(
                'data' => array()
            ))
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