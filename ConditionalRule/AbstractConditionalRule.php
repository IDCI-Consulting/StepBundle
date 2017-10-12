<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\ConditionalRule;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractConditionalRule implements ConditionalRuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function match(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);

        return $this->doMatch($resolver->resolve($options));
    }

    /**
     * Set default options.
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * Do match.
     *
     * @param array $options the options to match
     *
     * @return bool return true if the conditional rule match
     */
    abstract protected function doMatch(array $options = array());
}
