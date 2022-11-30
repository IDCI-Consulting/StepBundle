<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddUrlQueryParameterPathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(PathEventInterface $event, array $parameters = [])
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
            ->setRequired(['key'])
            ->setDefaults(['value' => '1'])
            ->setNormalizer('value', function (Options $options, $value) {
                return (string) $value;
            })
            ->setAllowedTypes('key', ['string'])
            ->setAllowedTypes('value', ['string', 'boolean', 'integer'])
        ;
    }
}
