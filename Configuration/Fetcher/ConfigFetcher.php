<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Fetcher;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigFetcher extends AbstractConfigurationFetcher
{
    /**
     * The config of the maps.
     *
     * @var array
     */
    protected $mapConfigs;

    /**
     * Constructor.
     *
     * @param array $mapConfigs The config of the maps.
     */
    public function __construct(
        array $mapConfigs
    )
    {
        $this->mapConfigs = $mapConfigs;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('name'))
            ->setAllowedTypes(array(
                'name' => array('string'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function doFetch(array $parameters = array())
    {
        $name = $parameters['name'];

        if (!isset($this->mapConfigs[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'No defined config found of name "%s"',
                $name
            ));
        }

        return $this->mapConfigs[$name];
    }
}