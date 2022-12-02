<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use IDCI\Bundle\StepBundle\ConditionalRule\ConditionalRuleRegistryInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class ConditionalDestinationPathType extends AbstractPathType
{
    /**
     * @var Environment
     */
    private $merger;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var ConditionalRuleRegistryInterface
     */
    private $conditionalRuleRegistry;

    /**
     * Constructor.
     */
    public function __construct(
        Environment $merger,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack,
        ConditionalRuleRegistryInterface $conditionalRuleRegistry
    ) {
        $this->merger = $merger;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->conditionalRuleRegistry = $conditionalRuleRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(['source'])
            ->setDefaults([
                'default_destination' => null,
                'destinations' => [],
                'stop_navigation' => false,
            ])
            ->setAllowedTypes('source', ['string'])
            ->setAllowedTypes('destinations', ['array'])
            ->setAllowedTypes('stop_navigation', ['string', 'bool'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(array $steps, array $options = []): PathInterface
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
    public function doResolveDestination(array $options, NavigatorInterface $navigator): ?string
    {
        $user = null;
        if (null !== $this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();
        }

        $stopNavigationRules = $options['stop_navigation'];
        if (true === $stopNavigationRules) {
            return null;
        }

        if (is_string($stopNavigationRules)) {
            $template = $this->merger->createTemplate($stopNavigationRules);
            $rules = $template->render([
                'user' => $user,
                'session' => $this->requestStack->getSession()->all(),
                'flow_data' => $navigator->getFlow()->getData(),
            ]);

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
            $mergedRules = $template->render([
                'user' => $user,
                'session' => $this->requestStack->getSession()->all(),
                'flow_data' => $navigator->getFlow()->getData(),
            ]);

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
     */
    private function matchConditionalRules($rules): bool
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
