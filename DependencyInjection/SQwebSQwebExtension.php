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
        $config = $this->processConfiguration($configuration, $configs)['config'];

        $container->setParameter('id_site', $config['sqw_id_site']);
        $container->setParameter('sitename', $config['sqw_message']);
        $container->setParameter('debug', $config['sqw_debug']);
        $container->setParameter('targeting', $config['sqw_targeting']);
        $container->setParameter('beacon', $config['sqw_beacon']);
        $container->setParameter('dwide', $config['sqw_dwide']);
        $container->setParameter('lang', $config['sqw_lang']);
        $container->setParameter('message', $config['sqw_message']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
