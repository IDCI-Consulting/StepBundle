<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

class HtmlStepType extends AbstractStepType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setDefaults(array('content' => null))
            ->setAllowedTypes(array('content' => array('null', 'string')))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function doBuildNavigationStepForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', 'idci_step_form_html', array(
            'content' => $options['content'],
        ));
    }
}