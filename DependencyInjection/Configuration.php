<?php

namespace Hatimeria\RemotelogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hatimeria_remotelog');

        $rootNode
            ->children()
                ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('place')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('route')->defaultValue('')->end()
                ->scalarNode('level')->defaultValue('ERROR')->end()
                ->booleanNode('cli')->defaultTrue()->end()
                ->booleanNode('enabled')->defaultFalse()->end()
            ->end();

        return $treeBuilder;
    }
    
}
