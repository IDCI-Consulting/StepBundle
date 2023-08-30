<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use IDCI\Bundle\StepBundle\ConditionalRule\ConditionalRuleRegistryInterface;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ConditionalStopNavigationStepEventAction extends AbstractStepEventAction
{
    /**
     * @var ConditionalRuleRegistryInterface
     */
    private $conditionalRuleRegistry;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * Constructor.
     */
    public function __construct(
        ConditionalRuleRegistryInterface $conditionalRuleRegistry,
        UrlGeneratorInterface $router
    ) {
        $this->conditionalRuleRegistry = $conditionalRuleRegistry;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('rules', false)->setAllowedTypes('rules', ['bool', 'array', 'string'])->setNormalizer('rules', function (Options $options, $value) {
                if (is_array($value)) {
                    return $value;
                }

                return is_bool($value) ? $value : (bool) $value;
            })
            ->setDefault('final_destination', null)->setAllowedTypes('final_destination', ['null', 'string'])
            ->setDefault('query_parameters', [])->setAllowedTypes('query_parameters', ['array'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecute(StepEventInterface $event, array $parameters = [])
    {
        if (!$this->matchConditionalRules($parameters['rules'])) {
            return false;
        }

        if (null !== $parameters['final_destination']) {
            $event
                ->getNavigator()
                ->setFinalDestination(
                    $this->router->generate($parameters['final_destination'], $parameters['query_parameters'])
                )
            ;
        }

        $event->getNavigator()->stop();

        return true;
    }

    /**
     * Match the given conditional rules.
     *
     * @param mixed $rules the rules to check
     *
     * @return bool return true if the rules match, false otherwise
     */
    private function matchConditionalRules($rules): bool
    {
        if (is_bool($rules)) {
            return $rules;
        }

        foreach ($rules as $alias => $options) {
            $rule = $this->conditionalRuleRegistry->getRule($alias);

            if (!$rule->match($options)) {
                return false;
            }
        }

        return true;
    }
}
