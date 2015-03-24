<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;

class ChangeDataPathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(
        FormInterface $form,
        NavigatorInterface $navigator,
        $pathIndex,
        $parameters = array()
    )
    {
        foreach ($parameters['fields'] as $field) {
            $this->change(
                $navigator->getFlow(),
                $field['target']['path'],
                $field['target']['types'],
                $field['value']
            );
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

    /**
     * Change the flow data.
     *
     * @param FlowInterface $flow  The flow data to change.
     * @param array         $path  The target path to change.
     * @param array         $types The flow data types to look at.
     * @param mixed         $value The value.
     */
    protected function change(
        FlowInterface $flow,
        $path,
        $types,
        $value
    )
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
     * @param array $data  The flow data.
     * @param array $path  The target path to change.
     * @param mixed $value The value.
     *
     * @return array The changed data.
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