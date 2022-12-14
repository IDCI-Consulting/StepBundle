<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration\Fetcher;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationFetcher extends AbstractConfigurationFetcher
{
    protected $raw;

    /**
     * Constructor.
     */
    public function __construct(array $raw)
    {
        $this->raw = $raw;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('options', [])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function doFetch(array $parameters = []): array
    {
        return array_merge_recursive($this->raw, $parameters);
    }
}
