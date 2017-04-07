<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;

abstract class AbstractPathEventAction implements PathEventActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(PathEventInterface $event, array $parameters = array())
    {
        $resolver = new OptionsResolver();
        $resolver
            ->setDefaults(array(
                'logical_expression' => true,
            ))
            ->setNormalizers(array(
                'logical_expression' => function (Options $options, $value) {
                    return (bool)$value;
                }
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
     * @param PathEventInterface $event      The path event.
     * @param array              $parameters The resolved parameters.
     *
     * @return mixed
     */
    abstract protected function doExecute(PathEventInterface $event, array $parameters = array());
}
