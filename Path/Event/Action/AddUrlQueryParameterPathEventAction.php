<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;

class AddUrlQueryParameterPathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(PathEventInterface $event, array $parameters = array())
    {
        $event->getNavigator()->addUrlQueryParameter(
            $parameters['key'],
            $parameters['value']
        );

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(array('key'))
            ->setDefaults(array('value' => '1'))
            ->setNormalizer(
                'value',
                function (OptionsResolver $options, $value) {
                    return (string) $value;
                }
            )
            ->setAllowedTypes('key', array('string'))
            ->setAllowedTypes('value', array('string', 'boolean', 'integer'))
        ;
    }
}
