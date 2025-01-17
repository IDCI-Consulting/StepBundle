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
        $flowData = $event->getData();
        $newData = [];
        foreach ($parameters['fields'] as $field => $value) {
            $newValue = $flowData['_content'][$field] ?? $value;

            switch ($parameters['mode']) {
                case 'default':
                    if (true === $parameters['force']) {
                        $newValue = $value;
                    }

                    break;

                case 'replace':
                    $newValue = $value;

                    break;

                case 'merge':
                    if (is_array($newValue) && is_array($value)) {
                        if (true === $parameters['force']) {
                            $newValue = array_merge($newValue, $value);
                        } else {
                            $newValue = array_merge($value, $newValue);
                        }
                    }

                    break;
            }

            $newData[$field] = $newValue;
        }

        if ($event->getNavigator()->getCurrentStep()->getType() instanceof FormStepType) {
            $newData = array_replace_recursive($event->getData(), ['_content' => $newData]);
        }

        $event->setData($newData);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('force', false)->setAllowedTypes('force', ['bool'])
            ->setDefault('mode', 'default')->setAllowedValues('mode', ['default', 'replace', 'merge'])
            ->setDefault('fields', [])->setAllowedTypes('fields', ['array'])->setNormalizer('fields', function (Options $options, $value) {
                foreach ($value as $k => $v) {
                    if (preg_match('/(?P<key>\w+)\|\s*json$/', $k, $matches)) {
                        $value[$matches['key']] = json_decode($v, true);
                        unset($value[$k]);
                    }
                }

                return $value;
            })
        ;
    }
}
