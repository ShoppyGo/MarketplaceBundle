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

use PrestaShop\PrestaShop\Adapter\Entity\Cart;
use PrestaShop\PrestaShop\Adapter\Entity\Currency;
use PrestaShop\PrestaShop\Adapter\Entity\Order;
use PrestaShop\PrestaShop\Adapter\Entity\OrderCarrier;
use PrestaShop\PrestaShop\Adapter\Entity\OrderDetail;
use PrestaShop\PrestaShop\Core\Localization\CLDR\ComputingPrecision;
use PrestaShop\PrestaShop\ShoppyGo\MarketplaceBundle\Exception\NotSellerOderDetailsException;
use Psr\Log\LoggerInterface;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSellerOrder;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerOrderRepository;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerProductRepository;

class MarketplaceOrderSplit
{
    protected Order $mainOrder;
    protected int $idSeller;
    protected int|float $precision;

    public function __construct(
        private readonly MarketplaceSellerOrderRepository $marketplaceSellerOrderRepository,
        private readonly MarketplaceSellerProductRepository $marketplaceSellerProductRepository,
        private readonly LoggerInterface $logger

    ) {
        $this->marketplaceSellerOrderRepository->setSellerProductRepository($this->marketplaceSellerProductRepository);
    }

    public function doSplitOrder(): ?MarketplaceSellerOrder
    {
        $sellerOrder = $this->cloneMainOrderForSeller();
        $currency = new Currency($sellerOrder->id_currency);
        \Context::getContext()->currency = $currency;
        $selleOrderDetails = $this->cloneOrderDetails();
        if(0===count($selleOrderDetails)){
            throw new NotSellerOderDetailsException();
        }
        $sellerOrderCarrier = $this->cloneOrderCarrier();

        try {
            $sellerOrder->add();

            array_walk($selleOrderDetails, static function (OrderDetail $detail) use ($sellerOrder) {
                $detail->id_order = $sellerOrder->id;
                $detail->add();
            });

            $sellerOrderCarrier->id_order = $sellerOrder->id;
            $sellerOrderCarrier->add();
            $this->updateTotalOrder(
                new Cart($sellerOrder->id_cart),
                $sellerOrder,
                $this->precision,
                $sellerOrderCarrier->id_carrier
            );
            $id_order_state = $this->mainOrder->getCurrentState();
            $sellerOrder->setCurrentState($id_order_state);

            return $this->createSellerOrder($sellerOrder->id);

        } catch (\Exception $exception) {
            $sellerOrder->delete();
            array_walk($selleOrderDetails, static function (OrderDetail $detail) {
                if ($detail->id) {
                    $detail->delete();
                }
            });
            $this->logger->error('ShoppyGo main order: {mainorder}', ['mainorder' => $this->mainOrder]);
            $this->logger->error($exception->getMessage());
            $this->logger->error('ShoppyGo seller oder deleted {sellerorder}', ['sellerorder' => $sellerOrder->id]);
        }

        return null;
    }

    public function setIdSeller(int $idSeller): void
    {
        $this->idSeller = $idSeller;
    }

    public function setMainOrder(int $id_order): void
    {
        $this->mainOrder = new Order($id_order);
        $currency = new Currency($this->mainOrder->id_currency);
        $compute_precision = new ComputingPrecision();
        $this->precision = $compute_precision->getPrecision($currency->precision);
    }

    private function cloneMainOrderForSeller(): Order
    {
        $seller_order = clone $this->mainOrder;
        $seller_order->id = null;

        return $seller_order;
    }

    private function cloneOrderCarrier(): OrderCarrier
    {
        $id_order_carrier = $this->mainOrder->getIdOrderCarrier();
        $carrier = clone new OrderCarrier($id_order_carrier);
        $carrier->id = null;

        return $carrier;
    }

    /**
     * @return array<OrderDetail>
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    private function cloneOrderDetails(): array
    {
        $sellerDetails = [];

        /** @var array $detail */
        foreach ($this->mainOrder->getOrderDetailList() as $detail) {
            if (false === $this->marketplaceSellerProductRepository->isProductSeller(
                    (int)$detail['product_id'],
                    $this->idSeller
                )) {
                continue;
            }
            $sellerDetail = clone new OrderDetail($detail['id_order_detail']);
            $sellerDetail->id = null;
            $sellerDetails[] = $sellerDetail;
        }

        return $sellerDetails;
    }

    private function createSellerOrder(int $idOrder): MarketplaceSellerOrder
    {
        $sellerOrder = new MarketplaceSellerOrder();
        $sellerOrder->setIdSeller($this->idSeller);
        $sellerOrder->setIdOrder($idOrder);
        $sellerOrder->setIdOrderMain($this->mainOrder->id);
        $this->marketplaceSellerOrderRepository->save($sellerOrder);

        return $sellerOrder;
    }

    private function updateTotalOrder(Cart $cart, Order $sellerOrder, int|float $computingPrecision, int $id_carrier)
    {
        $sellerOrder->total_products = \Tools::ps_round(
            (float)$cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $sellerOrder->getCartProducts(), $id_carrier),
            $computingPrecision
        );
        $sellerOrder->total_products_wt = \Tools::ps_round(
            (float)$cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $sellerOrder->getCartProducts(), $id_carrier),
            $computingPrecision
        );
        $sellerOrder->total_discounts_tax_excl = \Tools::ps_round(
            (float)abs(
                $cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, $sellerOrder->getCartProducts(), $id_carrier)
            ),
            $computingPrecision
        );
        $sellerOrder->total_discounts_tax_incl = \Tools::ps_round(
            (float)abs($cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, $sellerOrder->getCartProducts(), $id_carrier)),
            $computingPrecision
        );
        $sellerOrder->total_discounts = $sellerOrder->total_discounts_tax_incl;

        #---- impostare gli importi di spedizione
        $sellerOrder->total_shipping_tax_excl = \Tools::ps_round(
            (float)$cart->getPackageShippingCost($id_carrier, false, null, $sellerOrder->getCartProducts()),
            $computingPrecision
        );
        $sellerOrder->total_shipping_tax_incl = \Tools::ps_round(
            (float)$cart->getPackageShippingCost($id_carrier, true, null, $sellerOrder->getCartProducts()),
            $computingPrecision
        );
        $sellerOrder->total_shipping = $sellerOrder->total_shipping_tax_incl;

        $carrier = null;
        if (null !== $carrier && Validate::isLoadedObject($carrier)) {
            $sellerOrder->carrier_tax_rate = $carrier->getTaxesRate(
                new Address((int)$cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')})
            );
        }
        #--------------------

        $sellerOrder->total_wrapping_tax_excl = \Tools::ps_round(
            (float)abs($cart->getOrderTotal(false, Cart::ONLY_WRAPPING, $sellerOrder->getCartProducts(), $id_carrier)),
            $computingPrecision
        );
        $sellerOrder->total_wrapping_tax_incl = \Tools::ps_round(
            (float)abs($cart->getOrderTotal(true, Cart::ONLY_WRAPPING, $sellerOrder->getCartProducts(), $id_carrier)),
            $computingPrecision
        );
        $sellerOrder->total_wrapping = $sellerOrder->total_wrapping_tax_incl;

        $sellerOrder->total_paid_tax_excl = \Tools::ps_round(
            (float)$cart->getOrderTotal(false, Cart::BOTH, $sellerOrder->getCartProducts(), $id_carrier),
            $computingPrecision
        );
        $sellerOrder->total_paid_tax_incl = \Tools::ps_round(
            (float)$cart->getOrderTotal(true, Cart::BOTH, $sellerOrder->getCartProducts(), $id_carrier),
            $computingPrecision
        );
        $sellerOrder->total_paid = $sellerOrder->total_paid_tax_incl;

        $sellerOrder->save();
    }
}
