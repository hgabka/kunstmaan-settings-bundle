<?php

namespace Hgabka\KunstmaanSettingsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hgabka_kunstmaan_settings');

        $rootNode
            ->children()
                ->scalarNode('editor_role')->cannotBeEmpty()->defaultValue('ROLE_SETTING_ADMIN')->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}
