<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

class HtmlStepFormType extends AbstractStepFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $content = $options['content'];
        if (null !== $form->getData('content')) {
            $content = $form->getData('content');
        }

        $view->vars = array_merge($view->vars, array(
            'content' => $content,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setDefaults(array(
                'content' => null,
            ))
            ->setAllowedTypes(array('content' => array('null', 'string')))
            ->setNormalizers(array(
                'attr' => function (Options $options, $value) {
                    if (!isset($value['class'])) {
                        $value['class'] = 'html_text';
                    }

                    return $value;
                }
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idci_step_step_form_html';
    }
}