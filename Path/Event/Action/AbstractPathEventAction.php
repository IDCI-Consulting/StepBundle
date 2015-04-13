<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

abstract class AbstractPathEventAction implements PathEventActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(
        FormEvent $event,
        NavigatorInterface $navigator,
        $pathIndex,
        array $parameters = array(),
        $data = null
    )
    {
        $resolver = new OptionsResolver();
        $this->setDefaultParameters($resolver);

        return $this->doExecute(
            $event,
            $navigator,
            $pathIndex,
            $resolver->resolve($parameters),
            $data
        );
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
     * @param FormEvent          $event      The form event.
     * @param NavigatorInterface $navigator  The navigator.
     * @param integer            $pathIndex  The path index.
     * @param array              $parameters The resolved parameters.
     * @param mixed              $data       The retrieved event data.
     */
    abstract protected function doExecute(
        FormEvent $event,
        NavigatorInterface $navigator,
        $pathIndex,
        $parameters = array(),
        $data = null
    );
}