<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;

class AddUrlQueryParameterPathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(
        PathEventInterface $event,
        array $parameters = array()
    )
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
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('key'))
            ->setDefaults(array('value' => '1'))
            ->setNormalizers(array(
                'value' => function(Options $options, $value) {
                    return (string)$value;
                }
            ))
            ->setAllowedTypes(array(
                'key'   => array('string'),
                'value' => array('string', 'boolean', 'integer'),
            ))
        ;
    }
}