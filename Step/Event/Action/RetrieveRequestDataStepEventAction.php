<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Step\Event\Action\AbstractStepEventAction;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;

class RetrieveRequestDataStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(StepEventInterface $event, array $parameters = array())
    {
        $requestData = get_object_vars($event->getNavigator()->getRequest());
        $data = $requestData[$parameters['property']];

        if ($data->has($parameters['key'])) {
            return $data->get($parameters['key']);
        }

        return $parameters['default'];
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(array('key'))
            ->setDefaults(array(
                'property' => 'query',
                'default' => null,
            ))
            ->setAllowedValues('property', array('attributes', 'request', 'query', 'server', 'files', 'cookies', 'headers'))
            ->setAllowedTypes('property', 'string')
            ->setAllowedTypes('key', 'string')
        ;
    }
}
