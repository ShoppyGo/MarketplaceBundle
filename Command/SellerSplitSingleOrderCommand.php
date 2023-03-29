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
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceOrderSplit;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSellerOrder;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerProductRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SellerSplitSingleOrderCommand extends Command
{
    public const SHOPPYGO_SPLIT_SINGLE_ORDER = 'shoppygo:split:single:order';
    protected Registry $registry;
    protected string $dbPrefix;
    protected MarketplaceSellerProductRepository $marketplaceSellerProductRepository;
    protected MarketplaceOrderSplit $orderSplitter;

    public function setDoctrine(Registry $registry): void
    {
        $this->registry = $registry;
    }

    public function setMarketplaceSellerProductRepository(MarketplaceSellerProductRepository $productRepo): void
    {
        $this->marketplaceSellerProductRepository = $productRepo;
    }

    public function setSplitOrder(MarketplaceOrderSplit $marketplaceOrderSplit)
    {
        $this->orderSplitter = $marketplaceOrderSplit;
    }

    protected function configure(): void
    {
        $this->setName(self::SHOPPYGO_SPLIT_SINGLE_ORDER)
            ->setDescription('Marletplace scan order and split')
            ->addArgument('idorder', InputArgument::OPTIONAL, 'Id order to split')
            ->addOption('dbprefix', null, InputOption::VALUE_OPTIONAL, 'Database prestashop prefix', 'ps_')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arguments = $input->getArguments();
        $options = $input->getOptions();
        $id_order = (int) $arguments['idorder'];
        $this->dbPrefix = $options['dbprefix'];

        $marketplaceSellerOrderRepository = $this->registry->getRepository(MarketplaceSellerOrder::class);

        $products = $this->getIdProductsFromOrder($id_order);
        if (count($products) === 0) {
            $output->writeln('ERROR: no products for order ' . $id_order);

            return 0;
        }
        $sellers = $marketplaceSellerOrderRepository->getSellers(
            $products
        );

        switch (count($sellers)) {
            case 1:
                $seller_order = new MarketplaceSellerOrder();
                $seller_order->setIdSeller($sellers[0]);
                $seller_order->setIdOrder($id_order);
                $seller_order->setIdOrderMain($id_order);
                $marketplaceSellerOrderRepository->save($seller_order);
                break;

            default:
                foreach ($sellers as $id_seller) {
                    $this->orderSplitter->setMainOrder($id_order);
                    $this->orderSplitter->setIdSeller($id_seller);
                    $this->orderSplitter->doSplitOrder();
                }

                break;
        }
        $output->writeln('Success: ');

        return 0;
    }

    /**
     * @param int $id_order
     *
     * @return array
     *
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    private function getIdProductsFromOrder(int $id_order): array
    {
        return $this->queryBuilder()
            ->from($this->dbPrefix . 'order_detail', 'pod')
            ->select('pod.product_id as id_product')
            ->where('pod.id_order = :id_order')
            ->setParameter('id_order', $id_order)
            ->execute()
            ->fetchAllAssociative()
        ;
    }

    private function queryBuilder(): QueryBuilder
    {
        return $this->registry->getConnection()
            ->createQueryBuilder()
        ;
    }
}
