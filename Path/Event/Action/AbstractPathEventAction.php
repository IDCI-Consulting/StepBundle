<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractPathEventAction implements PathEventActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(PathEventInterface $event, array $parameters = [])
    {
        $resolver = new OptionsResolver();
        $resolver
            ->setDefault('logical_expression', true)->setNormalizer('logical_expression', function (Options $options, $value) {
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
     * @param PathEventInterface $event      the path event
     * @param array              $parameters the resolved parameters
     *
     * @return mixed
     */
    abstract protected function doExecute(PathEventInterface $event, array $parameters = []);
}
