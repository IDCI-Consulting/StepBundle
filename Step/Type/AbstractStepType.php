<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Step\Step;
use IDCI\Bundle\StepBundle\Step\StepInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractStepType implements StepTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('pre_step_content', null)
            ->setDefault('title', null)->setAllowedTypes('title', ['null', 'string'])
            ->setDefault('display_title', true)->setAllowedTypes('display_title', ['bool'])
            ->setDefault('nav_title', null)->setAllowedTypes('nav_title', ['null', 'string'])
            ->setDefault('description', null)->setAllowedTypes('description', ['null', 'string'])
            ->setDefault('nav_description', null)->setAllowedTypes('nav_description', ['null', 'string'])
            ->setDefault('translation_domain', null)->setAllowedTypes('translation_domain', ['null', 'string'])
            ->setDefault('is_first', false)->setAllowedTypes('is_first', ['bool', 'string'])->setNormalizer('is_first', function (Options $options, $value) {
                return (bool) $value;
            })
            ->setDefault('data', null)->setAllowedTypes('data', ['null', 'array'])
            ->setDefault('prevent_previous', false)->setAllowedTypes('prevent_previous', ['bool', 'string'])->setNormalizer('prevent_previous', function (Options $options, $value) {
                return (bool) $value;
            })
            ->setDefault('prevent_next', false)->setAllowedTypes('prevent_next', ['bool', 'string'])->setNormalizer('prevent_next', function (Options $options, $value) {
                return (bool) $value;
            })
            ->setDefault('previous_options', ['label' => '< Previous'])->setAllowedTypes('previous_options', ['array'])
            ->setDefault('js', null)->setAllowedTypes('js', ['null', 'string'])
            ->setDefault('css', null)->setAllowedTypes('css', ['null', 'string'])
            ->setDefault('attr', [])->setAllowedTypes('attr', ['array'])
            ->setDefault('events', [])->setAllowedTypes('events', ['array'])
            ->setDefault('serialization_mapping', null)->setAllowedTypes('serialization_mapping', ['null', 'array'])
            ->setDefault('save_content', false)->setAllowedTypes('save_content', ['bool', 'string'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildStep(string $name, array $options = []): StepInterface
    {
        return new Step($name, $this, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function prepareNavigation(NavigatorInterface $navigator, array $options): array
    {
        return $options;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function buildNavigationStepForm(FormBuilderInterface $builder, array $options);

    /**
     * {@inheritdoc}
     */
    public function getDataTypeMapping(array $options): array
    {
        if (null === $options['serialization_mapping']) {
            return [];
        }

        return $options['serialization_mapping'];
    }
}
