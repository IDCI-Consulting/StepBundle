<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Fetcher;

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
     * {@inheritdoc}
     */
    public function doFetch(array $parameters = array())
    {
        return $this->raw;
    }
}
