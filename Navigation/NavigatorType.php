<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NavigatorType extends AbstractType
{
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
        $this->buildSubmit($builder, $options);
    }

    /**
     * Build step.
     *
     * @param FormBuilderInterface $builder The builder.
     * @param array                $options The options.
     */
    public function buildStep(FormBuilderInterface $builder, array $options)
    {
        $currentStep = $options['navigator']->getCurrentStep();
        $configuration = $currentStep->getConfiguration();

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
    public function buildSubmit(FormBuilderInterface $builder, array $options)
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
        return AbstractNavigator::getName();
    }
}