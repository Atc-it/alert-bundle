<?php

namespace Atc\AlertBundle\DependencyInjection;

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
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('atc_alert');

        $rootNode
            ->children()
                ->scalarNode('mail_from_default')->end()
                ->scalarNode('sms_from_default')->end()
                ->scalarNode('sms_url')->end()
                ->scalarNode('sms_key')->end()
                ->scalarNode('sms_secret')->end()
                ->scalarNode('sms_prefix')->end()
                ->scalarNode('mailjet_public')->end()
                ->scalarNode('mailjet_private')->end()
                ->scalarNode('retarus_username')->end()
                ->scalarNode('retarus_password')->end()
                ->scalarNode('retarus_source')->end()
                ->scalarNode('retarus_encoding')->end()
                ->scalarNode('retarus_bill_code')->end()
                ->scalarNode('retarus_status_requested')->end()
                ->scalarNode('retarus_flash')->end()
                ->scalarNode('retarus_validity_min')->end()
                ->scalarNode('retarus_max_parts')->end()
                ->scalarNode('retarus_qos')->end()
                ->scalarNode('retarus_soap_url')->end()
            ->end();

        return $treeBuilder;
    }
}
