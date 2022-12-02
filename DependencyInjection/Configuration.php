<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * See {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('idci_step');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('maps')
                    ->defaultValue([])
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->isRequired()->end()
                            ->arrayNode('options')
                                ->defaultValue([])->prototype('variable')->end()
                            ->end()
                            ->arrayNode('data')
                                ->defaultValue([])->prototype('variable')->end()
                            ->end()
                            ->arrayNode('steps')
                                ->defaultValue([])->prototype('variable')->end()
                            ->end()
                            ->arrayNode('paths')
                                ->defaultValue([])->prototype('variable')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('serialization')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('mapping')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('form_types')
                                    ->defaultValue([])
                                    ->useAttributeAsKey('id')
                                        ->prototype('array')
                                        ->children()
                                            ->scalarNode('type')->isRequired()->end()
                                            ->arrayNode('groups')
                                                ->defaultValue([])
                                                ->prototype('scalar')
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->append($this->addStepTypesNode())
            ->append($this->addPathTypesNode())
            ->append($this->addPathEventActionsNode())
            ->append($this->addStepEventActionsNode())
        ;

        return $treeBuilder;
    }

    /**
     * Add step types node.
     */
    protected function addStepTypesNode(): NodeDefinition
    {
        $builder = new TreeBuilder('step_types');
        $node = $builder->getRootNode();
        $node
            ->defaultValue([])
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('parent')->defaultNull()->end()
                    ->booleanNode('abstract')->defaultFalse()->end()
                    ->scalarNode('description')->defaultNull()->end()
                    ->arrayNode('extra_form_options')
                        ->defaultValue([])->prototype('variable')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Add path types node.
     */
    protected function addPathTypesNode(): NodeDefinition
    {
        $invalidPathTypeName = function ($types) {
            foreach ($types as $key => $type) {
                if (!in_array($key, ['abstract', 'conditional_destination', 'single', 'end'])) {
                    return true;
                }
            }

            return false;
        };

        $builder = new TreeBuilder('path_types');
        $node = $builder->getRootNode();
        $node
            ->defaultValue([])
            ->useAttributeAsKey('name')
            ->validate()
            ->ifTrue($invalidPathTypeName)
                ->thenInvalid('A path type name is invalid')
            ->end()
            ->prototype('array')
                ->children()
                    ->scalarNode('parent')->defaultNull()->end()
                    ->booleanNode('abstract')->defaultFalse()->end()
                    ->scalarNode('description')->defaultNull()->end()
                    ->arrayNode('extra_form_options')
                        ->defaultValue([])->prototype('variable')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Add path event actions node.
     */
    protected function addPathEventActionsNode(): NodeDefinition
    {
        $builder = new TreeBuilder('path_event_actions');
        $node = $builder->getRootNode();
        $node
            ->defaultValue([])
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('parent')->defaultNull()->end()
                    ->booleanNode('abstract')->defaultFalse()->end()
                    ->scalarNode('description')->defaultNull()->end()
                    ->arrayNode('extra_form_options')
                        ->defaultValue([])->prototype('variable')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Add step event actions node.
     */
    protected function addStepEventActionsNode(): NodeDefinition
    {
        $builder = new TreeBuilder('step_event_actions');
        $node = $builder->getRootNode();
        $node
            ->defaultValue([])
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('parent')->defaultNull()->end()
                    ->booleanNode('abstract')->defaultFalse()->end()
                    ->scalarNode('description')->defaultNull()->end()
                    ->arrayNode('extra_form_options')
                        ->defaultValue([])->prototype('variable')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
