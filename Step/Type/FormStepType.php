<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use IDCI\Bundle\StepBundle\Serialization\SerializationMapper;
use IDCI\Bundle\StepBundle\Step\Type\Form\FormStepFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormStepType extends AbstractStepType
{
    /**
     * @var SerializationMapper
     */
    protected $serializationMapper;

    /**
     * Constructor.
     *
     * @param SerializationMapper $serializationMapper the serialization mapper
     */
    public function __construct(SerializationMapper $serializationMapper)
    {
        $this->serializationMapper = $serializationMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(['builder'])
            ->setAllowedTypes('builder', ['Symfony\Component\Form\FormBuilderInterface'])
            ->setDefault('save_content', true)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildNavigationStepForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('_content', FormStepFormType::class, [
            'label' => $options['title'],
            'translation_domain' => $options['translation_domain'],
            'display_title' => $options['display_title'],
            'builder' => $options['builder'],
            'error_bubbling' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDataTypeMapping(array $options): array
    {
        $mapping = parent::getDataTypeMapping($options);
        foreach ($options['builder']->all() as $key => $field) {
            if (isset($mapping[$key])) {
                continue;
            }

            $mapping[$key] = $this
                ->serializationMapper
                ->map(
                    'form_types',
                    $field->getType()->getBlockPrefix()
                )
            ;
        }

        return $mapping;
    }
}
