<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BackButtonType extends SubmitType
{
    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return SubmitType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'idci_step_back_button';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return $this->getName();
    }
}
