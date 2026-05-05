<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use IDCI\Bundle\StepBundle\Step\Event\Action\AbstractStepEventAction;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddFlashMessageStepEventAction extends AbstractStepEventAction
{
    public function __construct(
        protected RequestStack $requestStack,
    ) {
    }

    protected function doExecute(StepEventInterface $event, array $parameters = [])
    {
        $this->requestStack->getSession()->getFlashBag()->add($parameters['type'], $parameters['message']);

        return true;
    }

    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('type')->setAllowedTypes('type', ['string'])
            ->setRequired('message')->setAllowedTypes('message', ['string'])
        ;
    }
}
