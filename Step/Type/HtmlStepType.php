<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormBuilderInterface;

class HtmlStepType extends AbstractStepType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(Options $resolver)
    {
        parent::setDefaultOptions($resolver);

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
        $builder->add('content', 'idci_step_step_form_html', array(
            'content' => $options['content'],
            'display_title' => $options['display_title'],
        ));
    }
}
