<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use IDCI\Bundle\StepBundle\ConditionalRule\ConditionalRuleRegistryInterface;

class ConditionalStopNavigationStepEventAction extends AbstractStepEventAction
{
    /**
     * @var ConditionalRuleRegistryInterface
     */
    private $conditionalRuleRegistry;

    /**
     * Constructor
     *
     * @param ConditionalRuleRegistryInterface $conditionalRuleRegistry The conditional rule registry.
     */
    public function __construct(ConditionalRuleRegistryInterface $conditionalRuleRegistry)
    {
        $this->conditionalRuleRegistry = $conditionalRuleRegistry;
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'rules'             => false,
                'final_destination' => null,
            ))
            ->setAllowedTypes(array(
                'rules'             => array('bool', 'array'),
                'final_destination' => array('null', 'string'),
            ))
            ->setNormalizers(array(
                'rules' => function (Options $options, $value) {
                    if (is_array($value)) {
                        return $value;
                    }

                    return is_bool($value) ? $value : (bool) $value;
                }
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecute(
        StepEventInterface $event,
        array $parameters = array()
    ) {
        if (!$this->matchConditionalRules($parameters['rules'])) {
            return false;
        }

        if (null !== $parameters['final_destination']) {
            $event
                ->getNavigator()
                ->setFinalDestination($parameters['final_destination'])
            ;
        }

        $event->getNavigator()->stop();

        return true;
    }

    /**
     * Match the given conditional rules.
     *
     * @param mixed $rules The rules to check.
     *
     * @return boolean Return true if the rules match, false otherwise.
     */
    private function matchConditionalRules($rules)
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
