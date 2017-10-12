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
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('idci_step');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->arrayNode('maps')
                    ->defaultValue(array())
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->isRequired()->end()
                            ->arrayNode('options')
                                ->defaultValue(array())->prototype('variable')->end()
                            ->end()
                            ->arrayNode('data')
                                ->defaultValue(array())->prototype('variable')->end()
                            ->end()
                            ->arrayNode('steps')
                                ->defaultValue(array())->prototype('variable')->end()
                            ->end()
                            ->arrayNode('paths')
                                ->defaultValue(array())->prototype('variable')->end()
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
                                    ->defaultValue(array())
                                    ->useAttributeAsKey('id')
                                        ->prototype('array')
                                        ->children()
                                            ->scalarNode('type')->isRequired()->end()
                                            ->arrayNode('groups')
                                                ->defaultValue(array())
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
     * addStepTypesNode.
     *
     * @return ArrayNodeDefinition|NodeDefinition
     */
    protected function addStepTypesNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('step_types');

        $node
            ->defaultValue(array())
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('parent')->defaultNull()->end()
                    ->booleanNode('abstract')->defaultFalse()->end()
                    ->scalarNode('description')->defaultNull()->end()
                    ->arrayNode('extra_form_options')
                        ->defaultValue(array())->prototype('variable')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * addPathTypesNode.
     *
     * @return ArrayNodeDefinition|NodeDefinition
     */
    protected function addPathTypesNode()
    {
        $invalidPathTypeName = function ($types) {
            foreach ($types as $key => $type) {
                if (!in_array($key, array('abstract', 'conditional_destination', 'single', 'end'))) {
                    return true;
                }
            }

            return false;
        };

        $builder = new TreeBuilder();
        $node = $builder->root('path_types');

        $node
            ->defaultValue(array())
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
                        ->defaultValue(array())->prototype('variable')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * addPathEventActionsNode.
     *
     * @return ArrayNodeDefinition|NodeDefinition
     */
    protected function addPathEventActionsNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('path_event_actions');

        $node
            ->defaultValue(array())
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('parent')->defaultNull()->end()
                    ->booleanNode('abstract')->defaultFalse()->end()
                    ->scalarNode('description')->defaultNull()->end()
                    ->arrayNode('extra_form_options')
                        ->defaultValue(array())->prototype('variable')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * addStepEventActionsNode.
     *
     * @return ArrayNodeDefinition|NodeDefinition
     */
    protected function addStepEventActionsNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('step_event_actions');

        $node
            ->defaultValue(array())
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('parent')->defaultNull()->end()
                    ->booleanNode('abstract')->defaultFalse()->end()
                    ->scalarNode('description')->defaultNull()->end()
                    ->arrayNode('extra_form_options')
                        ->defaultValue(array())->prototype('variable')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
