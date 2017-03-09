<?php

namespace Atc\AlertBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
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

        $ymlLoader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $ymlLoader->load('services.yml');

        $container->setParameter('atc_alert.mail_from_default', $config['mail_from_default']);
        $container->setParameter('atc_alert.sms_from_default', $config['sms_from_default']);
        $container->setParameter('atc_alert.sms_url', $config['sms_url']);
        $container->setParameter('atc_alert.sms_key', $config['sms_key']);
        $container->setParameter('atc_alert.sms_secret', $config['sms_secret']);
        $container->setParameter('atc_alert.sms_prefix', $config['sms_prefix']);
        $container->setParameter('atc_alert.mailjet_public', $config['mailjet_public']);
        $container->setParameter('atc_alert.mailjet_private', $config['mailjet_private']);

        $container->setParameter('atc_alert.retarus_username', $config['retarus_username']);
        $container->setParameter('atc_alert.retarus_password', $config['retarus_password']);
        $container->setParameter('atc_alert.retarus_source', $config['retarus_source']);
        $container->setParameter('atc_alert.retarus_encoding', $config['retarus_encoding']);
        $container->setParameter('atc_alert.retarus_bill_code', $config['retarus_bill_code']);
        $container->setParameter('atc_alert.retarus_status_requested', $config['retarus_status_requested']);
        $container->setParameter('atc_alert.retarus_flash', $config['retarus_flash']);
        $container->setParameter('atc_alert.retarus_validity_min', $config['retarus_validity_min']);
        $container->setParameter('atc_alert.retarus_max_parts', $config['retarus_max_parts']);
        $container->setParameter('atc_alert.retarus_qos', $config['retarus_qos']);
        $container->setParameter('atc_alert.retarus_soap_url', $config['retarus_soap_url']);
    }
}
