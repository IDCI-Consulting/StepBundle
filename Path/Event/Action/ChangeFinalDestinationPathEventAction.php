<?php

/**
 * @author:  Benjamin TARDY  <benjamin.tardy@tessi.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeFinalDestinationPathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(PathEventInterface $event, array $parameters = [])
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
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['final_destination'])
            ->setAllowedTypes('final_destination', ['string'])
        ;
    }
}
