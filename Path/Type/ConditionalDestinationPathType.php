<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use IDCI\Bundle\StepBundle\ConditionalRule\ConditionalRuleRegistryInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class ConditionalDestinationPathType extends AbstractPathType
{
    /**
     * @var \Twig_Environment
     */
    private $merger;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ConditionalRuleRegistryInterface
     */
    private $conditionalRuleRegistry;

    /**
     * Constructor.
     *
     * @param \Twig_Environment                $merger                  the twig merger
     * @param SecurityContextInterface         $securityContext         the security context
     * @param SessionInterface                 $session                 the session
     * @param ConditionalRuleRegistryInterface $conditionalRuleRegistry the conditional rule registry
     */
    public function __construct(
        \Twig_Environment                $merger,
        SecurityContextInterface         $securityContext,
        SessionInterface                 $session,
        ConditionalRuleRegistryInterface $conditionalRuleRegistry
    ) {
        $this->merger = $merger;
        $this->securityContext = $securityContext;
        $this->session = $session;
        $this->conditionalRuleRegistry = $conditionalRuleRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setRequired(array('source'))
            ->setDefaults(array(
                'default_destination' => null,
                'destinations' => array(),
                'stop_navigation' => false,
            ))
            ->setAllowedTypes('source', array('string'))
            ->setAllowedTypes('destinations', array('array'))
            ->setAllowedTypes('stop_navigation', array('string', 'bool'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(array $steps, array $options = array())
    {
        $path = parent::buildPath($steps, $options);
        $path->setSource($steps[$options['source']]);

        foreach ($options['destinations'] as $name => $rule) {
            $path->addDestination($steps[$name]);
        }

        if (null !== $options['default_destination']) {
            $path->addDestination($steps[$options['default_destination']]);
        }

        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public function doResolveDestination(array $options, NavigatorInterface $navigator)
    {
        $user = null;
        if (null !== $this->securityContext->getToken()) {
            $user = $this->securityContext->getToken()->getUser();
        }

        $stopNavigationRules = $options['stop_navigation'];
        if (true === $stopNavigationRules) {
            return null;
        } else {
            $template = $this->merger->createTemplate($stopNavigationRules);
            $rules = $template->render(array(
                'user' => $user,
                'session' => $this->session->all(),
                'flow_data' => $navigator->getFlow()->getData(),
            ));

            if ($this->matchConditionalRules($rules)) {
                return null;
            }
        }

        foreach ($options['destinations'] as $name => $rules) {
            $mergedRules = $rules;
            if (is_array($rules)) {
                $mergedRules = json_encode($rules);
            }

            $template = $this->merger->createTemplate($mergedRules);
            $mergedRules = $template->render(array(
                'user' => $user,
                'session' => $this->session->all(),
                'flow_data' => $navigator->getFlow()->getData(),
            ));

            if (is_array($rules)) {
                $mergedRules = json_decode($mergedRules, true);
            }

            if ($this->matchConditionalRules($mergedRules)) {
                return $name;
            }

            continue;
        }

        return $options['default_destination'];
    }

    /**
     * Match the given conditional rules.
     *
     * @param mixed $rules the rules to check
     *
     * @return bool return true if the rules match, false otherwise
     */
    private function matchConditionalRules($rules)
    {
        if (!is_array($rules)) {
            return (bool) $rules;
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
