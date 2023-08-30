<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Doctrine\Common\Util\Inflector;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use IDCI\Bundle\StepBundle\Step\Type\FormStepType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransformDataStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(StepEventInterface $event, array $parameters = [])
    {
        $formData = $event->getData();
        $step = $event->getNavigator()->getCurrentStep();

        if (empty($formData) || $step->getType() instanceof FormStepType && empty($formData['_content'])) {
            return;
        }

        foreach ($parameters['fields'] as $field => $method) {
            $destination = $field;
            $options = [];
            // Decode complexe configuration
            if (is_array($method)) {
                $options = isset($method['options']) ? $method['options'] : [];
                $destination = isset($method['destination']) ? $method['destination'] : $field;
                $method = isset($method['method']) ? $method['method'] : null;
            }

            $transformer = sprintf('transform%s', Inflector::classify($method));
            if ($step->getType() instanceof FormStepType) {
                $formData['_content'][$destination] = forward_static_call_array(
                    [$this, $transformer],
                    [$formData['_content'][$field], $options]
                );
            } else {
                $formData[$destination] = forward_static_call_array(
                    [$this, $transformer],
                    [$formData[$field], $options]
                );
            }
        }

        $event->setData($formData);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('fields', [])->setAllowedTypes('fields', ['array'])
        ;
    }

    /**
     * Upper transformer.
     *
     * @param string $value   The value to transform
     * @param array  $options Options used to transform the given value
     *
     * @return mixed
     */
    public static function transformUpper($value, array $options = [])
    {
        if (!is_string($value)) {
            return $value;
        }

        return mb_strtoupper($value);
    }

    /**
     * Lower transformer.
     *
     * @param string $value   The value to transform
     * @param array  $options Options used to transform the given value
     *
     * @return mixed
     */
    public static function transformLower($value, array $options = [])
    {
        if (!is_string($value)) {
            return $value;
        }

        return mb_strtolower($value);
    }

    /**
     * Replace transformer.
     *
     * @param string $value   The value to transform
     * @param array  $options Options used to transform the given value
     *
     * @return mixed
     */
    public static function transformReplace($value, array $options = [])
    {
        if (!is_string($value)) {
            return $value;
        }

        $pairs = [];
        if (isset($options['pairs']) && is_array($options['pairs'])) {
            $pairs = $options['pairs'];
        }

        return strtr($value, $pairs);
    }
}
