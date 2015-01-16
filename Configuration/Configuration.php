<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dumb');

        $rootNode
          ->children()
                ->scalarNode('name')
                    ->isRequired()
                ->end()
                ->arrayNode('options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('browsing')->defaultValue('linear')->end()
                        ->scalarNode('data_store')->defaultValue('session')->end()
                        ->scalarNode('first_step_name')->defaultValue(null)->end()
                    ->end()
                ->end()
                ->arrayNode('data')
                    ->prototype('variable')->end()
                ->end()
                ->arrayNode('steps')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('dumb')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')->isRequired()->end()
                            ->arrayNode('options')
                                ->defaultValue(array())
                                ->prototype('variable')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('paths')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')->isRequired()->end()
                            ->arrayNode('options')
                                ->defaultValue(array())
                                ->prototype('variable')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}