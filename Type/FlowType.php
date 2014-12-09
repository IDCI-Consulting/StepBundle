<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Builder\StepBuilderInterface;
use IDCI\Bundle\StepBundle\View\StepView;
use IDCI\Bundle\StepBundle\StepInterface;

class FlowType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'flow';
    }
}