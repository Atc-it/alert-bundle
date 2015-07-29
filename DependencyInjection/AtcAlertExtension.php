<?php

namespace Atc\Bundle\AlertBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AtcAlertExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        $container->setParameter('atc_alert.mail_from_default', $config['mail_from_default']);
        $container->setParameter('atc_alert.sms_from_default', $config['sms_from_default']);
        $container->setParameter('atc_alert.sms_url', $config['sms_url']);
        $container->setParameter('atc_alert.sms_key', $config['sms_key']);
        $container->setParameter('atc_alert.sms_secret', $config['sms_secret']);
        $container->setParameter('atc_alert.sms_prefix', $config['sms_prefix']);
        $container->setParameter('atc_alert.mandrill_secret', $config['mandrill_secret']);
    }
}

