<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractStepEventAction implements StepEventActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(StepEventInterface $event, array $parameters = [])
    {
        $resolver = new OptionsResolver();
        $resolver
            ->setDefaults([
                'logical_expression' => true,
            ])
            ->setNormalizer('logical_expression', function (Options $options, $value) {
                return (bool) $value;
            })
        ;

        $this->setDefaultParameters($resolver);
        $resolvedParameters = $resolver->resolve($parameters);

        if ($resolvedParameters['logical_expression']) {
            return $this->doExecute($event, $resolvedParameters);
        }
    }

    /**
     * Set default parameters.
     */
    abstract protected function setDefaultParameters(OptionsResolver $resolver);

    /**
     * Do execute action.
     *
     * @param StepEventInterface $event      the step event
     * @param array              $parameters the resolved parameters
     *
     * @return mixed
     */
    abstract protected function doExecute(StepEventInterface $event, array $parameters = []);
}
