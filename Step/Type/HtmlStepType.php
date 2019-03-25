<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use IDCI\Bundle\StepBundle\Step\Type\Form\HtmlStepFormType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class HtmlStepType extends AbstractStepType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults(array(
                'content' => null,
                'attr' => array('class' => 'html_text'),
            ))
            ->setAllowedTypes('content', array('null', 'string'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildNavigationStepForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', HtmlStepFormType::class, array(
            'label' => $options['title'],
            'display_title' => $options['display_title'],
            'content' => $options['content'],
        ));
    }
}
