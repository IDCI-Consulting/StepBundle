<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Util\Inflector;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use IDCI\Bundle\StepBundle\Step\Type\FormStepType;

class TransformDataStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(StepEventInterface $event, array $parameters = array())
    {
        $formData = $event->getData();
        $step = $event->getNavigator()->getCurrentStep();
        $configuration = $step->getConfiguration();

        if (empty($formData) ||
            $configuration['type'] instanceof FormStepType && empty($formData['_data'])
        ) {
            return;
        }

        foreach ($parameters['fields'] as $field => $method) {
            $destination = $field;
            // Decode complexe configuration
            if (is_array($method)) {
                $destination = isset($method['destination']) ? $method['destination'] : $field;
                $method = isset($method['method']) ? $method['method'] : null;
            }

            $transformer = sprintf('transform%s', Inflector::classify($method));
            if ($configuration['type'] instanceof FormStepType) {
                $formData['_data'][$destination] = forward_static_call_array(
                    array($this, $transformer),
                    array($formData['_data'][$field])
                );
            } else {
                $formData[$destination] = forward_static_call_array(
                    array($this, $transformer),
                    array($formData[$field])
                );
            }
        }

        $event->setData($formData);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array('fields' => array()))
            ->setAllowedTypes(array(
                'fields' => array('array'),
            ))
        ;
    }

    /**
     * Upper transformer.
     *
     * @param string $value The value to transform
     *
     * @return mixed
     */
    public static function transformUpper($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        return mb_strtoupper($value);
    }

    /**
     * Lower transformer.
     *
     * @param string $value The value to transform
     *
     * @return mixed
     */
    public static function transformLower($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        return mb_strtolower($value);
    }
}
