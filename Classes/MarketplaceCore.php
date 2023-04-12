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

namespace ShoppyGo\MarketplaceBundle\Classes;

use PrestaShop\PrestaShop\Adapter\LegacyContext;
use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use ShoppyGo\MarketplaceBundle\Adapter\Entity\Seller;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceEmployeeSeller;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSeller;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceEmployeeSellerRepository;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerRepository;

class MarketplaceCore
{
    protected ?MarketplaceEmployeeSeller $marketplaceEmployeeeller;
    protected MarketplaceEmployeeSellerRepository $marketplaceEmployeeSellerRepository;
    protected MarketplaceSellerRepository $marketplaceSellerRepository;
    /**
     * @var CommandBusInterface
     */
    private LegacyContext $legacyContext;

    public function __construct(
        LegacyContext $context,
        MarketplaceEmployeeSellerRepository $marketplaceEmployeeSellerRepository,
        MarketplaceSellerRepository $marketplaceSellerRepository,
    ) {
        $this->legacyContext = $context;
        $this->marketplaceEmployeeSellerRepository = $marketplaceEmployeeSellerRepository;
        $this->setMarketplaceSeller();
        $this->marketplaceSellerRepository = $marketplaceSellerRepository;
    }

    public function getEmployee(): \Employee
    {
        return $this->legacyContext->getContext()->employee;
    }

    public function getEmployeeId(): int
    {
        return (int)$this->getEmployee()->id;
    }

    public function getMarketplaceEmployeSeller(): MarketplaceSeller
    {
        return $this->marketplaceSellerRepository->findOneBy(['id_seller' => $this->getSellerId()]);
    }

    public function getSeller(int $id = 0): Seller
    {
        return new Seller($id ?: $this->marketplaceEmployeeeller->getIdSeller());
    }

    /**
     * @return int
     */
    public function getSellerId(): int
    {
        return (int)$this->marketplaceEmployeeeller->getIdSeller();
    }

    public function isEmployStaff()
    {
        return false === $this->isEmployeeSeller();
    }

    public function isEmployeeSeller()
    {
        return (bool)$this->marketplaceEmployeeeller;
    }

    private function setMarketplaceSeller()
    {
        $this->marketplaceEmployeeeller =
            $this->marketplaceEmployeeSellerRepository->findOneBy(['id_employee' => $this->getEmployeeId()]);
    }
}
