<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class AbstractPathType implements PathTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }
}
