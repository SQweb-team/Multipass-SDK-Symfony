<?php

namespace SQweb\SQwebBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('s_qweb_s_qweb');
        $rootNode
            ->children()
                ->arrayNode('config')
                    ->children()
                        ->integerNode('sqw_id_site')->end()
                        ->scalarNode('sqw_sitename')->end()
                        ->booleanNode('sqw_debug')->end()
                        ->booleanNode('sqw_targeting')->end()
                        ->booleanNode('sqw_beacon')->end()
                        ->booleanNode('sqw_dwide')->end()
                        ->scalarNode('sqw_lang')->end()
                        ->scalarNode('sqw_message')->end()
                    ->end()
                ->end() // twitter
            ->end()
        ;
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
