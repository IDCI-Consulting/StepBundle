<?php

/**
 * @author:  Benjamin TARDY  <benjamin.tardy@tessi.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;

class ChangeFinalDestinationPathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(
        PathEventInterface $event,
        array $parameters = array()
    )
    {
        $event
            ->getNavigator()
            ->setFinalDestination($parameters['final_destination'])
        ;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('final_destination'))
            ->setAllowedTypes(array(
                'final_destination' => array('string')
            ))
        ;
    }
}
