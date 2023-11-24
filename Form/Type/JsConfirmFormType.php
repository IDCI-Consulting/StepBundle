<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Camille SCHWARZ <camille54460@gmail.com>
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

        $conditions = $options['conditions'];
        foreach ($options['conditions'] as $i => $condition) {
            if (!empty($condition['observed_fields'])) {
                $observedFields = [];
                foreach ($condition['observed_fields'] as $observedFieldPath => $expectedValue) {
                    $observedFields[self::guessObservedFieldId($observedFieldPath, $view->parent->children['_content'])] = $expectedValue;
                }
                $conditions[$i]['observed_fields'] = $observedFields;
            }
        }
        $view->vars['conditions'] = $conditions;
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
            ->setDefault('conditions', function (OptionsResolver $conditionsResolver): void {
                $conditionsResolver
                    ->setPrototype(true)
                    ->setDefault('message', 'Are you sure ?')->setAllowedTypes('message', ['null', 'string'])
                    ->setDefault('observed_fields', null)->setAllowedTypes('observed_fields', ['null', 'array'])
                ;
            })
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
