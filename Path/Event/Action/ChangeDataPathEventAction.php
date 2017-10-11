<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;

class ChangeDataPathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(PathEventInterface $event, array $parameters = array())
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
            ->setDefaults(array('fields' => array()))
            ->setAllowedTypes('fields', array('array'))
        ;
    }

    /**
     * Change the flow data.
     *
     * @param FlowInterface $flow  the flow data to change
     * @param array         $path  the target path to change
     * @param array         $types the flow data types to look at
     * @param mixed         $value the value
     */
    protected function change(FlowInterface $flow, $path, $types, $value)
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
     * @param array $data  the flow data
     * @param array $path  the target path to change
     * @param mixed $value the value
     *
     * @return array the changed data
     */
    protected function doChange(array $data, array $path, $value)
    {
        $field = array_shift($path);

        if (!isset($data[$field]) && !empty($path)) {
            $data[$field] = array();
        }

        if (!empty($path)) {
            $data[$field] = $this->doChange($data[$field], $path, $value);
        } else {
            $data[$field] = $value;
        }

        return $data;
    }
}
