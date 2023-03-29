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

namespace ShoppyGo\MarketplaceBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SetupMarketplaceCommand extends Command
{
    protected Registry $registry;
    protected TranslatorInterface $translator;

    public function installTab($controller_name, $route_name, $label, $parent, $icon = '')
    {
        if (\Tab::getIdFromClassName($controller_name)) {
            return true;
        }

        $tab = new \Tab();
        $tab->active = 1;
        $tab->class_name = $controller_name;

        if ($route_name) {
            $tab->route_name = $route_name;
        }
        if ($icon !== '') {
            $tab->icon = $icon;
        }

        $tab->name = array();
        foreach (\Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = $this->translator->trans(
                $label,
                array(),
                'Admin.Marketplace.Menu',
                $lang['locale']
            );
        }

        $tab->id_parent = (int)\Tab::getIdFromClassName($parent);
        $tab->module = null;

        return $tab->add();
    }

    public function setDoctrine(Registry $registry): void
    {
        $this->registry = $registry;
    }

    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    protected function configure(): void
    {
        $this->setName('shoppygo:setup:marketplace')
            ->setDescription('Marletplace setup')
            ->addOption('create', null, InputOption::VALUE_NONE, 'Create marketplace tables')
            ->addOption('drop', null, InputOption::VALUE_NONE, 'Drop marketplace tables')
            ->addOption('menu', null, InputOption::VALUE_NONE, 'Add marketplace menu')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('menu')) {
            $this->addMarketplaceMenu($output);
        }
        //TODO devo impostare gòli hook per il marketplace.
        //     in questo momento sono questi Hook
        //     displayAdminMarketplaceDashboardTop
        //     displayAdminMarketplaceDashboardBottom
        //     displayAdminMarketplaceDashboardLeft
        //     displayAdminMarketplaceDashboardCenter
        //     displayAdminMarketplaceDashboardRight



        $setup_database = $this->getApplication()
            ->find('shoppygo:setup:database')
        ;
        $options = [
            '--create',
        ];
        $input_data = new \Symfony\Component\Console\Input\ArrayInput($options);
        $setup_database->run($input_data, $output);
    }

    private function addMarketplaceMenu(OutputInterface $output)
    {
        $adminMenu = [
            [
                'controller' => 'AdminMarketplace',
                'route_name' => '',
                'label'      => 'Marketplace',
                'parent'     => 'DEFAULT',
                'icon'       => 'shopping_cart',
            ],
            [      #--- parent seller menu
                   'controller' => 'AdminMarketplaceSeller',
                   'route_name' => '',
                   'label'      => 'Your seller configuration',
                   'parent'     => 'DEFAULT',
                   'icon'       => 'settings_applications',
            ],
            [
                'controller' => 'AdminSuppliers',
                'route_name' => 'admin_suppliers_index',
                'label'      => 'Sellers',
                'parent'     => 'AdminMarketplace',
                'icon'       => '',
            ],
            [
                'controller' => 'AdminMarketplaceConfiguration',
                'route_name' => 'admin_marketplace_configuration',
                'label'      => 'Configuration',
                'parent'     => 'AdminMarketplace',
                'icon'       => '',
            ],
            [
                'controller' => 'AdminMarketplaceCommission',
                'route_name' => 'admin_marketplace_marketplace_commission_index',
                'label'      => 'Commission',
                'parent'     => 'AdminMarketplace',
                'icon'       => '',
            ],
            [
                'controller' => 'AdminMarketplaceSeller',
                'route_name' => 'admin_employees_index',
                'label'      => 'Sellers',
                'parent'     => 'AdminMarketplace',
                'icon'       => '',
            ],
            #-------------------
            # submenu seller
            #
            [
                'controller' => 'AdminMarketplaceSellerShipping',
                'route_name' => 'admin_marketplace_seller_shipping',
                'label'      => 'Configure shipping cost',
                'parent'     => 'AdminMarketplaceSeller',
                'icon'       => '',
            ],

        ];
        foreach ($adminMenu as $menu) {
            $res_install_menu = $this->installTab(
                $menu['controller'],
                $menu['route_name'],
                $menu['label'],
                $menu['parent'],
                $menu['icon']

            );
            if (!$res_install_menu) {
                break;
            }
        }
    }
}
