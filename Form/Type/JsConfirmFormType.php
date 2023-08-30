<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JsConfirmFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['observed_id'] = sprintf('idci_step_navigator__path_%s', $options['path_index']);
        $view->vars['message'] = $options['message'];

        if (!empty($options['observed_fields'])) {
            $observedFields = [];
            foreach ($options['observed_fields'] as $observedFieldPath => $expectedValue) {
                $observedFields[self::guessObservedFieldId($observedFieldPath, $view->parent->children['_content'])] = $expectedValue;
            }
            $view->vars['observed_fields'] = $observedFields;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return HiddenType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('path_index')->setAllowedTypes('path_index', ['integer'])
            ->setDefault('message', null)->setAllowedTypes('message', ['null', 'string'])->setNormalizer('message', function (Options $options, $value) {
                if (null === $value) {
                    return 'Are you sure ?';
                }

                return $value;
            })
            ->setDefault('observed_fields', [])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'idci_step_action_form_js_confirm';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return $this->getName();
    }

    public function guessObservedFieldId(string $fieldPath, FormView $view): string
    {
        foreach (explode('.', $fieldPath) as $fieldName) {
            if (!isset($view->children[$fieldName])) {
                throw new \RuntimeException('The given field path doesn\'t exist: %s', $fieldPath);
            }

            $view = $view->children[$fieldName];
        }

        return $view->vars['id'];
    }
}
