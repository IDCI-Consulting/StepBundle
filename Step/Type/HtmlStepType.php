<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use IDCI\Bundle\StepBundle\Step\Type\Form\HtmlStepFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlStepType extends AbstractStepType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'content' => null,
                'attr' => ['class' => 'html_text'],
            ])
            ->setAllowedTypes('content', ['null', 'string'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildNavigationStepForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('_content', HtmlStepFormType::class, [
            'label' => $options['title'],
            'display_title' => $options['display_title'],
            'content' => $options['content'],
        ]);
    }
}
