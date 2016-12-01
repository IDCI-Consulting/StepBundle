<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Fetcher;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\ExtraFormBundle\Exception\FetchConfigurationException;

abstract class AbstractConfigurationFetcher implements ConfigurationFetcherInterface
{
    /**
     * {@inheritDoc}
     */
    public function fetch(array $parameters = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultParameters($resolver);

        return $this->doFetch($resolver->resolve($parameters));
    }

    /**
     * Set default parameters.
     *
     * @param OptionsResolverInterface $resolver
     */
    abstract protected function setDefaultParameters(OptionsResolverInterface $resolver);

    /**
     * Fetch the configuration.
     *
     * @param  array $parameters
     *
     * @return array
     *
     * @throw  FetchConfigurationException
     */
    abstract protected function doFetch(array $parameters = array());
}
