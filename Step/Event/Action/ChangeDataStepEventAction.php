<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class ChangeDataStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(
        FormInterface $form,
        NavigatorInterface $navigator,
        $parameters = array(),
        $data = null
    )
    {
        $step = $navigator->getCurrentStep();
        $configuration = $step->getConfiguration();

        if ($configuration['type'] instanceof \IDCI\Bundle\StepBundle\Step\Type\FormStepType) {
            $form = $form->get('_data');
        }

        foreach ($parameters['fields'] as $field => $newValue) {
            $form->get($field)->setData($newValue);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array('fields' => array()))
            ->setAllowedTypes(array(
                'fields' => array('array')
            ))
        ;
    }
}