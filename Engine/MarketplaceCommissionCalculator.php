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

namespace ShoppyGo\MarketplaceBundle\Engine;

use ShoppyGo\MarketplaceBundle\Mapper\OrderMapper;
use PrestaShop\PrestaShop\Adapter\Entity\Order;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSeller;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerOrderRepository;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerRepository;

class MarketplaceCommissionCalculator
{
    private MarketplaceSellerRepository $sellerRepository;
    private MarketplaceSellerOrderRepository $sellerOrderRepository;
    private CommissionCalculatorInterface $calculatorEngine;

    public function __construct(
        MarketplaceSellerRepository $sellerRepository,
        MarketplaceSellerOrderRepository $sellerOrderRepository,
        CommissionCalculatorInterface $calculatorEngine
    ) {
        $this->sellerRepository = $sellerRepository;
        $this->sellerOrderRepository = $sellerOrderRepository;
        $this->calculatorEngine = $calculatorEngine;
    }

    public function calculateCommissions(): array
    {
        $sellers = $this->sellerRepository->findAll();
        $commissions = [];

        foreach ($sellers as $seller) {
            $commissions[] = $this->calucalteSellerCommission($seller);
        }

        return $commissions;
    }

    public function calucalteSellerCommission(MarketplaceSeller $seller): array
    {
        $sellerOrders = $this->sellerOrderRepository->findBy(['id_seller' => $seller->getIdSeller()]);
        $commissions = [];
        foreach ($sellerOrders as $sellerOrder) {
            $order = new Order($sellerOrder->getIdOrder());
            $order_dto = OrderMapper::toMarketplaceOrderCommissionDTO($order);
            $commission = $this->calculatorEngine->calculateCommission($order_dto, $seller->getMarketplaceCommission());

            $commissions[] = [
                'seller'          => $seller,
                'id_seller'       => $seller->getIdSeller(),
                'id_order'        => $order->id,
                'order_reference' => $order->reference,
                'total'           => $commission,
            ];
        }

        return $commissions;
    }
}
