<?php

namespace SQweb\SQwebBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class SQwebSQwebExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        dump($config);

        $container->setParameter('id_site', $config['id_site']);
        $container->setParameter('sitename', $config['sitename']);
        $container->setParameter('debug', $config['debug']);
        $container->setParameter('targeting', $config['targeting']);
        $container->setParameter('beacon', $config['beacon']);
        $container->setParameter('dwide', $config['dwide']);
        $container->setParameter('lang', $config['lang']);
        $container->setParameter('message', $config['message']);

        /* These following configs are for button customization */

        $container->setParameter('login', $config['login']);
        $container->setParameter('connected', $config['connected']);
        $container->setParameter('support', $config['support']);
        $container->setParameter('btn_noads', $config['btn_noads']);
        $container->setParameter('login_tiny', $config['login_tiny']);
        $container->setParameter('connected_s', $config['connected_s']);
        $container->setParameter('btn_unlimited', $config['btn_unlimited']);
        $container->setParameter('connected_tiny', $config['connected_tiny']);
        $container->setParameter('connected_support', $config['connected_support']);

        /* - - - - - - - - - - - - - - - - - - - - - - - - - - */

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
