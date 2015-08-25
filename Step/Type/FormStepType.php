<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormBuilderInterface;
use IDCI\Bundle\StepBundle\Serialization\SerializationMapper;

class FormStepType extends AbstractStepType
{
    /**
     * @var SerializationMapper
     */
    protected $serializationMapper;

    /**
     * Constructor
     *
     * @param SerializationMapper $serializationMapper The serialization mapper.
     */
    public function __construct(SerializationMapper $serializationMapper)
    {
        $this->serializationMapper = $serializationMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setRequired(array('builder'))
            ->setDefaults(array(
                'display_title' => true,
            ))
            ->setAllowedTypes(array(
                'builder'       => array('Symfony\Component\Form\FormBuilderInterface'),
                'display_title' => array('bool'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function doBuildNavigationStepForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('_data', 'idci_step_step_form_form', array(
            'label'         => $options['title'],
            'builder'       => $options['builder'],
            'display_title' => $options['display_title'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getDataTypeMapping($options)
    {
        $mapping = parent::getDataTypeMapping($options);
        foreach($options['builder']->all() as $key => $field) {
            if (isset($mapping[$key])) {
                continue;
            }

            $mapping[$key] = $this
                ->serializationMapper
                ->map(
                    'form_types',
                    $field->getType()->getName()
                )
            ;
        }

        return $mapping;
    }
}