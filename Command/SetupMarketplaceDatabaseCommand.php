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

class SetupMarketplaceDatabaseCommand extends Command
{
    protected Registry $registry;

    public function setDoctrine(Registry $registry): void
    {
        $this->registry = $registry;
    }

    protected function configure(): void
    {
        $this->setName('shoppygo:setup:database')
            ->setDescription('Marletplace database install')
            ->addOption('create', null, InputOption::VALUE_NONE, 'Create marketplace tables')
            ->addOption('drop', null, InputOption::VALUE_NONE, 'Drop marketplace tables')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = $input->getOptions();
        $sqls = [];
        $type = '';
        if (true === $options['create']) {
            $type = 'create. Marketplace is installed';
            $sqls = $this->createSql();
        }
        if (true === $options['drop']) {
            $type = 'drop. Marketplace is uninstalled';
            $sqls = $this->dropSql();
        }

        $conn = $this->registry->getConnection();

        $output->writeln('Type: ' . $options['create'] ? 'create' : 'drop');
        foreach ($sqls as $sql) {
            $output->writeln('sql: ' . $sql);
            $conn->executeQuery($sql);
        }

        $output->writeln('Success: ' . $type);

        return 0;
    }

    private function createSql(): array
    {
        //----- tabella seller
        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'marketplace_seller` (
                    `id_supplier` int(11) NOT NULL,
                    `id_category` int(11) NOT NULL ,
                    `id_marketplace_commission` int(11) NOT NULL ,
                    `vat_number` varchar(25) NOT NULL ,
                    `website` varchar(255) NOT NULL ,
                    `email` varchar(100) NOT NULL ,
                    `return_policy` text NOT NULL ,
                    KEY (`id_supplier`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'marketplace_employee_seller` (
                    `id_employee` int(11) NOT NULL ,
                    `id_supplier` int(11) NOT NULL,
                    PRIMARY KEY  (`id_employee`),
                    KEY (`id_supplier`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        //----- tabella commissioni
        // create sql table marketplace_commission
        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'marketplace_commission` (
                      `id_marketplace_commission` int(11) NOT NULL AUTO_INCREMENT,
                      `commission_name` varchar(255) NOT NULL,
                      `fixed_commission` float NOT NULL,
                      `commission_percentage` float NOT NULL,
                      `total_products_net_of_vat` tinyint(1) NOT NULL,
                      `total_net_of_discount` tinyint(1) NOT NULL,
                      `shipping_value_net_of_vat` tinyint(1) NOT NULL,
                      `total_vat` tinyint(1) NOT NULL,
                      `total_general` tinyint(1) NOT NULL,
                      PRIMARY KEY (`id_marketplace_commission`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                    ';

        //----- tabella spedizioni
        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'marketplace_seller_shipping` (
                    `id_shipping` int(11) NOT NULL AUTO_INCREMENT,
                    `id_supplier` int(11) NOT NULL ,
                    `id_tax_rules_group` int(11) null,
                    `carrier_name` varchar(255) NOT NULL ,
                    `from_total` decimal(8,2),
                    `to_total` decimal(8,2),
                    `type` char(1),
                    `shipping_cost` decimal(4,2),
                    PRIMARY KEY  (`id_shipping`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        //------- categorie abilitate
        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'marketplace_category` (
                    `id_category` int(11) NOT NULL ,
                    `seller` boolean,
                    PRIMARY KEY ( `id_category`),
                    KEY (`id_category`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        //------- order status
        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'marketplace_seller_order_status` (
                    `id_order_state` int(11) NOT NULL ,
                    `seller` boolean,
                    PRIMARY KEY ( `id_order_state`),
                    KEY (`id_order_state`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        //------- order
        $sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'marketplace_seller_order
                    (
                        id_order      int  not null,
                        id_supplier   int  not null,
                        id_order_main int  not null,
                        split         tinyint(1) default 0 null,
                        primary key (id_order)
                    )
                        charset = utf8;';

        $sql[] = 'CREATE INDEX  marketplace_id_order_main_index
                        on ' . _DB_PREFIX_ . 'marketplace_seller_order (id_order_main);';

        $sql[] = 'CREATE INDEX  marketplace_id_supplier_index
                        on ' . _DB_PREFIX_ . 'marketplace_seller_order (id_supplier);';

        //------- order
        $sql[] = 'CREATE TABLE if NOT EXISTS `' . _DB_PREFIX_ . 'marketplace_seller_category` (`id_category` int(11) NOT null ,
                    `id_supplier` int(11) NOT null,
                    KEY(`id_supplier`, `id_category`)
                ) ENGINE = InnoDB default CHARSET = utf8;';

        return $sql;
    }

    private function dropSql(): array
    {
        return [
            'DROP TABLE if EXISTS ' . _DB_PREFIX_ . 'marketplace_employee_seller',
            'DROP TABLE if EXISTS ' . _DB_PREFIX_ . 'marketplace_seller_shipping',
            'DROP TABLE if EXISTS ' . _DB_PREFIX_ . 'marketplace_category',
            'DROP TABLE if EXISTS ' . _DB_PREFIX_ . 'marketplace_seller_order',
            'DROP TABLE if EXISTS ' . _DB_PREFIX_ . 'marketplace_seller_category',
        ];
    }
}
