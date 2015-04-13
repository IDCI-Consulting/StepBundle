<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Util\Inflector;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Step\Type\FormStepType;

class TransformDataStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(
        FormEvent $event,
        NavigatorInterface $navigator,
        $parameters = array(),
        $data = null
    )
    {
        $form     = $event->getForm();
        $formData = $event->getData();
        $step = $navigator->getCurrentStep();
        $configuration = $step->getConfiguration();

        if (empty($formData) ||
            $configuration['type'] instanceof FormStepType && empty($formData['_data'])
        ) {
            return;
        }

        foreach ($parameters['fields'] as $field => $method) {
            $transformer = sprintf('transform%s', Inflector::classify($method));
            if ($configuration['type'] instanceof FormStepType) {
                $formData['_data'][$field] = forward_static_call_array(
                    array($this, $transformer),
                    array($formData['_data'][$field])
                );
            } else {
                $formData[$field] = forward_static_call_array(
                    array($this, $transformer),
                    array($formData[$field])
                );
            }
        }

        $event->setData($formData);
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

    /**
     * Upper transformer
     *
     * @param string $value The value to transform
     * @return mixed
     */
    public static function transformUpper($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        return strtoupper($value);
    }

    /**
     * Lower transformer
     *
     * @param string $value The value to transform
     * @return mixed
     */
    public static function transformLower($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        return strtolower($value);
    }
}