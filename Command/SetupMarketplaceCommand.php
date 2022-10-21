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

class SetupMarketplaceCommand extends Command
{
    protected Registry $registry;

    public function setDoctrine(Registry $registry): void
    {
        $this->registry = $registry;
    }

    protected function configure(): void
    {
        $this->setName('shoppygo:setup')
            ->setDescription('Marletplace setup')
            ->addOption('create', null, InputOption::VALUE_NONE, 'Create marketplace tables')
            ->addOption('drop', null, InputOption::VALUE_NONE, 'Drop marketplace tables')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $setup_database = $this->getApplication()->find('shoppygo:setup:database');
        $options = [
            '--create',
        ];
        $input_data = new \Symfony\Component\Console\Input\ArrayInput($options);
        $setup_database->run($input_data, $output);
    }
}
