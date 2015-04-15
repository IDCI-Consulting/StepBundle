<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;

abstract class AbstractPathEventAction implements PathEventActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(
        PathEventInterface $event,
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
     * @param PathEventInterface $event      The path event.
     * @param array              $parameters The resolved parameters.
     */
    abstract protected function doExecute(
        PathEventInterface $event,
        array $parameters = array()
    );
}