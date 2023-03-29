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
namespace Tests\ShoppyGo\MarketplaceBundle\Engine;

use ShoppyGo\MarketplaceBundle\Engine\MarketplaceSellerCommissionCalculator;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceCommission;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSeller;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSellerOrder;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerOrderRepository;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerRepository;
use PHPUnit\Framework\TestCase;
use Order;

class MarketplaceSellerCommissionCalculatorTest extends TestCase
{
    public function testCalculateCommissions()
    {
        $sellerRepositoryMock = $this->getMockBuilder(MarketplaceSellerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $sellerOrderRepositoryMock = $this->getMockBuilder(MarketplaceSellerOrderRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $calculatorEngineMock = $this->getMockBuilder(CommissionCalculatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $marketplaceCommission = new MarketplaceCommission();
        $marketplaceCommission->setCommissionPercentage(10);

        $marketplaceSeller = new MarketplaceSeller();
        $marketplaceSeller->setIdSeller(1);
        $marketplaceSeller->setIdCategory(1);
        $marketplaceSeller->setMarketplaceCommission($marketplaceCommission);

        $marketplaceSellerOrder = new MarketplaceSellerOrder();
        $marketplaceSellerOrder->setIdOrder(1);
        $marketplaceSellerOrder->setIdSeller(1);
        $marketplaceSellerOrder->setIdOrderMain(1);

        $order = new class extends Order {
            public function getTotalPaid()
            {
                return 100;
            }
        };

        $calculatorEngineMock
            ->method('calculateCommission')
            ->willReturn(10);

        $sellerRepositoryMock
            ->method('findAll')
            ->willReturn([$marketplaceSeller]);

        $sellerOrderRepositoryMock
            ->method('findBy')
            ->willReturn([$marketplaceSellerOrder]);

        $calculator = new MarketplaceSellerCommissionCalculator(
            $sellerRepositoryMock,
            $sellerOrderRepositoryMock,
            $calculatorEngineMock
        );

        $result = $calculator->calculateCommissions();

        $this->assertCount(1, $result);
        $this->assertSame($marketplaceSeller, $result[0]['seller']);
        $this->assertSame(1, $result[0]['id_order']);
        $this->assertSame(10, $result[0]['commission']);
    }
}
