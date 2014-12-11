<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class MultiplePathType extends AbstractPathType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('label', 'source', 'destinations'))
            ->setDefaults(array('lablel' => 'next'))
            ->setAllowedTypes('label', 'string')
            ->setAllowedTypes('source', 'string')
            ->setAllowedTypes('destinations', 'array')
        ;
    }
}