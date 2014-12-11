<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EndPathType extends AbstractPathType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'end';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('label', 'source', 'storageProvider'))
            ->setDefaults(array('lablel' => 'end'))
            ->setAllowedTypes('label', 'string')
            ->setAllowedTypes('source', 'string')
            ->setAllowedTypes('storageProvider', 'string')
        ;
    }
}