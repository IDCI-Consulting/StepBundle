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
            ->setDefault('attr', ['class' => 'html_text'])
            ->setDefault('content', null)->setAllowedTypes('content', ['null', 'string'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildNavigationStepForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('_content', HtmlStepFormType::class, [
            'label' => $options['title'],
            'translation_domain' => $options['translation_domain'],
            'display_title' => $options['display_title'],
            'content' => $options['content'],
        ]);
    }
}
