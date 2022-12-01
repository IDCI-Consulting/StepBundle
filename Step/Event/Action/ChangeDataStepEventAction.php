<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use IDCI\Bundle\StepBundle\Step\Type\FormStepType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeDataStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(StepEventInterface $event, array $parameters = [])
    {
        $step = $event->getNavigator()->getCurrentStep();
        $data = $parameters['fields'];
        $form = $event->getForm();

        if ($step->getType() instanceof FormStepType) {
            $data = array_replace_recursive(
                $event->getData(),
                ['_content' => $data]
            );
        }

        $event->setData($data);

        foreach ($data as $field => $newValue) {
            $form->get($field)->setData($newValue);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['fields' => []])
            ->setNormalizer('fields', function (Options $options, $value) {
                foreach ($value as $k => $v) {
                    if (preg_match('/(?P<key>\w+)\|\s*json$/', $k, $matches)) {
                        $value[$matches['key']] = json_decode($v, true);
                        unset($value[$k]);
                    }
                }

                return $value;
            })
            ->setAllowedTypes('fields', ['array'])
        ;
    }
}
