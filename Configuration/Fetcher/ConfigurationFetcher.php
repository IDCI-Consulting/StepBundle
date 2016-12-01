<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Fetcher;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigurationFetcher extends AbstractConfigurationFetcher
{
    protected $raw;

    /**
     * Constructor.
     *
     * @param array $raw
     */
    public function __construct(array $raw)
    {
        $this->raw = $raw;
    }

    /**
     * {@inheritDoc}
     */
    public function doFetch(array $parameters = array())
    {
        return array_merge($this->raw, $parameters);
    }

    /**
     * {@inheritDoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setOptional(array('name', 'steps', 'paths', 'options'))
            ->setAllowedTypes(array(
                'name'    => array('null', 'string'),
                'steps'   => array('null', 'array'),
                'paths'   => array('null', 'array'),
                'options' => array('null', 'array'),
            ))
        ;
    }
}
