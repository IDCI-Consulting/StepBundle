<?php

/**
 * @author:  Benjamin TARDY  <benjamin.tardy@tessi.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use IDCI\Bundle\StepBundle\Flow\FlowInterface;

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
