<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SinglePathType extends AbstractPathType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'single';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('label', 'source', 'destination', 'listeners'))
            ->setDefaults(array('lablel' => 'next'))
            ->setAllowedTypes('label', 'string')
            ->setAllowedTypes('source', 'string')
            ->setAllowedTypes('destination', 'string')
            ->setAllowedTypes('listeners', 'array')
        ;
    }
}