<?php
/**
 * Copyright since 2022 Bwlab of Luigi Massa and Contributors
 * Bwlab of Luigi Massa is an Italy Company
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@shoppygo.io so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade ShoppyGo to newer
 * versions in the future. If you wish to customize ShoppyGo for your
 * needs please refer to https://docs.shoppygo.io/ for more information.
 *
 * @author    Bwlab and Contributors <contact@shoppygo.io>
 * @copyright Since 2022 Bwlab of Luigi Massa and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace ShoppyGo\MarketplaceBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ShoppyGoMarketplaceExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

//         $container->setParameter('shoppy_go_maketplace.bar', $config['bar']);
//         $container->setParameter('shoppy_go_maketplace.integer_foo', $config['integer_foo']);
//         $container->setParameter('shoppy_go_maketplace.integer_bar', $config['integer_bar']);
    }

    public function prepend(ContainerBuilder $container)
    {
//        $configs = $container->getExtensionConfig($this->getAlias());
//        $config = $this->processConfiguration(new Configuration(), $configs);
        // TODO: Set custom doctrine config
        $doctrineConfig = [];
//        $doctrineConfig['orm']['resolve_target_entities']['ShoppyGo\MaketplaceBundle\Entity\UserInterface'] = $config['user_provider'];
//        $doctrineConfig['orm']['mappings'][] = array(
//            'name' => 'ShoppyGoMaketplaceBundle',
//            'is_bundle' => true,
//            'type' => 'xml',
//            'prefix' => 'ShoppyGo\MaketplaceBundle\Entity'
//        );
//        $container->prependExtensionConfig('doctrine', $doctrineConfig);
        // TODO: Set custom twig config
        $twigConfig = [];
//        $twigConfig['globals']['shoppy_go_maketplace_bar_service'] = "@shoppy_go_maketplace.service";
//        $twigConfig['paths'][__DIR__.'/../Resources/views'] = "shoppy_go_maketplace";
//        $twigConfig['paths'][__DIR__.'/../Resources/public'] = "shoppy_go_maketplace.public";
//        $container->prependExtensionConfig('twig', $twigConfig);
    }

    public function getAlias()
    {
        return 'shoppy_go_marketplace';
    }
}
