<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;

class LinkFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge($view->vars, array(
            'href' => $options['href'],
            'target' => $options['target'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(Options $resolver)
    {
        $resolver
            ->setDefaults(array(
                'href' => 'javascript:void(0);',
                'target' => '_self',
            ))
            ->setNormalizer(
                'label',
                function (Options $options, $value) {
                    if (in_array($value, array(null, 'end'))) {
                        return $options['href'];
                    }

                    return $value;
                }
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idci_step_action_form_link';
    }
}
