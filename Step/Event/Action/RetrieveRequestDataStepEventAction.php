<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RetrieveRequestDataStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(StepEventInterface $event, array $parameters = [])
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
            ->setRequired(['key'])
            ->setDefaults([
                'property' => 'query',
                'default' => null,
            ])
            ->setAllowedValues('property', ['attributes', 'request', 'query', 'server', 'files', 'cookies', 'headers'])
            ->setAllowedTypes('property', 'string')
            ->setAllowedTypes('key', 'string')
        ;
    }
}
