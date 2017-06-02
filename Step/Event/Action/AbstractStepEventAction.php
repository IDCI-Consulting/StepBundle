<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
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
        $resolver
            ->setDefaults(array(
                'logical_expression' => true,
            ))
            ->setNormalizers(array(
                'logical_expression' => function(Options $options, $value) {
                    return (bool)$value;
                },
            ))
        ;

        $this->setDefaultParameters($resolver);
        $resolvedParameters = $resolver->resolve($parameters);

        if ($resolvedParameters['logical_expression']) {
            return $this->doExecute($event, $resolvedParameters);
        }
    }

    /**
     * Set default parameters.
     *
     * @param OptionsResolverInterface $resolver
     */
    abstract protected function setDefaultParameters(OptionsResolverInterface $resolver);

    /**
     * Do execute action.
     *
     * @param StepEventInterface $event      The step event.
     * @param array              $parameters The resolved parameters.
     *
     * @return mixed
     */
    abstract protected function doExecute(
        StepEventInterface $event,
        array $parameters = array()
    );
}
