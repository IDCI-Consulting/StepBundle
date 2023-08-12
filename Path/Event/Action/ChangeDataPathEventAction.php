<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeDataPathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(PathEventInterface $event, array $parameters = [])
    {
        foreach ($parameters['fields'] as $field) {
            $this->change(
                $event->getNavigator()->getFlow(),
                $field['target']['path'],
                $field['target']['types'],
                $field['value']
            );
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
            ->setAllowedTypes('fields', ['array'])
        ;
    }

    /**
     * Change the flow data.
     *
     * @param FlowInterface $flow  the flow data to change
     * @param string        $path  the target path to change
     * @param array         $types the flow data types to look at
     * @param mixed         $value the value
     */
    protected function change(FlowInterface $flow, string $path, array $types, $value)
    {
        foreach ($types as $type) {
            $dataGetter = sprintf('get%s', ucfirst($type));
            $dataSetter = sprintf('set%s', ucfirst($type));

            $data = $flow->getData()->$dataGetter();
            $changedData = $this->doChange($data, explode('.', $path), $value);
            $flow->getData()->$dataSetter($changedData);
        }
    }

    /**
     * Do the flow data change.
     *
     * @param mixed $data  the flow data
     * @param array $path  the target path to change
     * @param mixed $value the value
     *
     * @return array the changed data
     */
    protected function doChange($data, array $path, $value): array
    {
        $field = array_shift($path);

        if (is_object($data)) {
            $data = json_decode(json_encode($data), true);
        }

        if (!isset($data[$field]) && !empty($path)) {
            $data[$field] = [];
        }

        if (!empty($path)) {
            $data[$field] = $this->doChange($data[$field], $path, $value);
        } else {
            $data[$field] = $value;
        }

        return $data;
    }
}
