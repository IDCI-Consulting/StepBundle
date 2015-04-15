<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;

abstract class AbstractStepEventAction implements StepEventActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(
        StepEventInterface $event,
        array $parameters = array()
    )
    {
        $resolver = new OptionsResolver();
        $this->setDefaultParameters($resolver);

        return $this->doExecute($event, $resolver->resolve($parameters));
    }

    /**
     * Set default parameters.
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
    }

    /**
     * Do execute action.
     *
     * @param StepEventInterface $event      The step event.
     * @param array              $parameters The resolved parameters.
     */
    abstract protected function doExecute(
        StepEventInterface $event,
        array $parameters = array()
    );
}