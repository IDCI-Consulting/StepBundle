<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 *
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class CurrentStepHiddenType extends HiddenType
{
    public function getParent(): string
    {
        return HiddenType::class;
    }

    public function getName(): string
    {
        return 'idci_step_current_step';
    }

    public function getBlockPrefix(): string
    {
        return $this->getName();
    }
}
