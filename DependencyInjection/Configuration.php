<?php

namespace Anacona16\Bundle\DependentFormsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('dependent_forms');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('dependent_forms')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('class')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('parent_property')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('role')
                                ->defaultValue('IS_AUTHENTICATED_ANONYMOUSLY')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('no_result_msg')
                                ->defaultValue('No results were found')
                                ->cannotBeEmpty()
                            ->end()
                            ->booleanNode('show_loading')
                                ->defaultTrue()
                            ->end()
                            ->scalarNode('order_property')
                                ->defaultValue('id')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('order_direction')
                                ->defaultValue('ASC')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('property')
                                ->defaultValue(null)
                                ->cannotBeEmpty()
                            ->end()
                            ->booleanNode('property_complicated')
                                ->defaultFalse()
                            ->end()
                            ->booleanNode('case_insensitive')
                                ->defaultTrue()
                            ->end()
                            ->scalarNode('search')
                                ->defaultValue('begins_with')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('callback')
                                ->defaultValue(null)
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
