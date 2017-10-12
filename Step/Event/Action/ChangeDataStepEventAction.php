<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;

class ChangeDataStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(StepEventInterface $event, array $parameters = array())
    {
        $step = $event->getNavigator()->getCurrentStep();
        $configuration = $step->getConfiguration();
        $data = $parameters['fields'];

        if ($event->getName() === FormEvents::PRE_SET_DATA) {
            if ($configuration['type'] instanceof \IDCI\Bundle\StepBundle\Step\Type\FormStepType) {
                $data = array_replace_recursive(
                    $event->getData(),
                    array('_data' => $data)
                );
            }
            $event->setData($data);
        } elseif ($event->getName() === FormEvents::POST_SET_DATA) {
            $form = $event->getForm();
            if ($configuration['type'] instanceof \IDCI\Bundle\StepBundle\Step\Type\FormStepType) {
                $form = $form->get('_data');
            }

            foreach ($data as $field => $newValue) {
                $form->get($field)->setData($newValue);
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array('fields' => array()))
            ->setNormalizers(array(
                'fields' => function (OptionsResolver $options, $value) {
                    foreach ($value as $k => $v) {
                        if (preg_match('/(?P<key>\w+)\|\s*json$/', $k, $matches)) {
                            $value[$matches['key']] = json_decode($v, true);
                            unset($value[$k]);
                        }
                    }

                    return $value;
                },
            ))
            ->setAllowedTypes('fields', array('array'))
        ;
    }
}
