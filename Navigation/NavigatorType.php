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
            ->add('_step', 'hidden', array(
                'data' => $options['navigator']->getCurrentStep()->getName())
            )
        ;

        $this->buildSubmit($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function buildSubmit(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['navigator']->getAvailablePaths() as $i => $path) {
            $builder->add(sprintf('_path#%d', $i), 'submit', array(
                'label' => $path->getLabel(),
            ));
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