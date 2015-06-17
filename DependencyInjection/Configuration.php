<?php

namespace Atc\Bundle\AlertBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface {

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('atc_alert');

        $rootNode
            ->children()
                ->scalarNode('mail_from_default')->end()
                ->scalarNode('sms_url')->end()
                ->scalarNode('sms_key')->end()
                ->scalarNode('sms_secret')->end()
            ->end();



        return $treeBuilder;
    }

}
