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
            ->setDefaults([
                'title' => null,
                'display_title' => true,
                'nav_title' => null,
                'description' => null,
                'nav_description' => null,
                'is_first' => false,
                'pre_step_content' => null,
                'data' => null,
                'prevent_previous' => false,
                'prevent_next' => false,
                'previous_options' => [
                    'label' => '< Previous',
                ],
                'js' => null,
                'css' => null,
                'attr' => [],
                'events' => [],
                'serialization_mapping' => null,
            ])
            ->setAllowedTypes('title', ['null', 'string'])
            ->setAllowedTypes('display_title', ['bool'])
            ->setAllowedTypes('nav_title', ['null', 'string'])
            ->setAllowedTypes('description', ['null', 'string'])
            ->setAllowedTypes('nav_description', ['null', 'string'])
            ->setAllowedTypes('is_first', ['bool', 'string'])
            ->setAllowedTypes('data', ['null', 'array'])
            ->setAllowedTypes('prevent_previous', ['bool', 'string'])
            ->setAllowedTypes('prevent_next', ['bool', 'string'])
            ->setAllowedTypes('previous_options', ['array'])
            ->setAllowedTypes('js', ['null', 'string'])
            ->setAllowedTypes('css', ['null', 'string'])
            ->setAllowedTypes('attr', ['array'])
            ->setAllowedTypes('events', ['array'])
            ->setAllowedTypes('serialization_mapping', ['null', 'array'])
            ->setNormalizer('is_first', function (Options $options, $value) {
                return (bool) $value;
            })
            ->setNormalizer('prevent_previous', function (Options $options, $value) {
                return (bool) $value;
            })
            ->setNormalizer('prevent_next', function (Options $options, $value) {
                return (bool) $value;
            })
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
