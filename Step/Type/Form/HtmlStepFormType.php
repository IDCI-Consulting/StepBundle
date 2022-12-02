<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class HtmlStepFormType extends AbstractStepFormType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $content = $options['content'];
        if (is_string($form->getData('content')) && !empty($form->getData('content'))) {
            $content = $form->getData('content');
        }

        if ($options['display_title']) {
            $content = sprintf('<h1>%s</h1>%s', $this->translator->trans($options['label']), $content);
        }

        $view->vars = array_merge($view->vars, [
            'content' => $content,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults(['content' => null])
            ->setAllowedTypes('content', ['null', 'string'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'idci_step_step_form_html';
    }
}
