<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use IDCI\Bundle\StepBundle\Flow\FlowData;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurgeFlowDataPathEventAction extends AbstractPathEventAction
{
    public static $flowDataTypeMapping = [
        'data' => null,
        'reminded_data' => FlowData::TYPE_REMINDED,
        'retrieved_data' => FlowData::TYPE_RETRIEVED,
    ];

    /**
     * Returns whether the flow data type is valid or not.
     */
    protected function isValidFlowDataType(string $type): bool
    {
        return in_array($type, array_keys(self::$flowDataTypeMapping));
    }

    /**
     * Returns the flow data type.
     */
    protected function getFlowDataType(string $type): string
    {
        if (!$this->isValidFlowDataType($type)) {
            throw new \UnexpectedValueException(sprintf('The flow data type "%s" doesn\'t exist (valid flow data types are: "%s")', $type, implode('", "', array_keys(self::$flowDataTypeMapping))));
        }

        return self::$flowDataTypeMapping[$type];
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecute(PathEventInterface $event, array $parameters = [])
    {
        foreach ($parameters['steps'] as $stepName => $dataTypes) {
            foreach ($dataTypes as $type => $keys) {
                if (!is_array($keys)) {
                    $keys = [$keys];
                }

                try {
                    $stepData = $event
                        ->getNavigator()
                        ->getFlow()
                        ->getData()
                        ->getStepData($stepName, $this->getFlowDataType($type))
                    ;

                    if (!empty($keys)) {
                        foreach ($keys as $key) {
                            if (isset($stepData[$key])) {
                                unset($stepData[$key]);
                            }
                        }
                    } else {
                        $stepData = [];
                    }

                    $event
                        ->getNavigator()
                        ->getFlow()
                        ->getData()
                        ->setStepData($stepName, $stepData, $this->getFlowDataType($type))
                    ;
                } catch (\Exception $e) {
                    continue;
                }
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
            ->setRequired([
                'steps',
            ])
            ->setAllowedTypes('steps', ['array'])
            ->setNormalizer('steps', function (Options $options, $value) {
                foreach ($value as $stepName => $dataTypes) {
                    if (!is_array($dataTypes)) {
                        throw new \UnexpectedValueException(sprintf('The data type of the step "%s" is not an array', $stepName));
                    } else {
                        foreach ($dataTypes as $type => $keys) {
                            $this->getFlowDataType($type);
                            if (!is_array($keys)) {
                                throw new \UnexpectedValueException(sprintf('The purge defined field "%s:%s" must be an array', $stepName, $type));
                            }
                        }
                    }
                }

                return $value;
            })
        ;
    }
}
