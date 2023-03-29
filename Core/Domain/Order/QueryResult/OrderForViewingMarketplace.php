<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace ShoppyGo\MarketplaceBundle\Core\Domain\Order\QueryResult;

use DateTimeImmutable;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\LinkedOrdersForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderCustomerForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderDiscountsForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderDocumentsForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderForViewing as OrderForViewingPrestaShop;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderHistoryForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderInvoiceAddressForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderMessagesForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderPaymentsForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderPricesForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderProductsForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderReturnsForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderShippingAddressForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderShippingForViewing;
use PrestaShop\PrestaShop\Core\Domain\Order\QueryResult\OrderSourcesForViewing;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;

/**
 * Contains data about order for viewing
 */
class OrderForViewingMarketplace extends OrderForViewingPrestaShop
{
    protected MarketplaceCore $marketplaceCore;

    public function __construct(
        int $orderId,
        int $currencyId,
        int $carrierId,
        string $carrierName,
        int $shopId,
        string $reference,
        bool $isVirtual,
        string $taxMethod,
        bool $isTaxIncluded,
        bool $isValid,
        bool $hasBeenPaid,
        bool $hasInvoice,
        bool $isDelivered,
        bool $isShipped,
        bool $invoiceManagementIsEnabled,
        DateTimeImmutable $createdAt,
        ?OrderCustomerForViewing $customer,
        OrderShippingAddressForViewing $shippingAddress,
        OrderInvoiceAddressForViewing $invoiceAddress,
        OrderProductsForViewing $products,
        OrderHistoryForViewing $history,
        OrderDocumentsForViewing $documents,
        OrderShippingForViewing $shipping,
        OrderReturnsForViewing $returns,
        OrderPaymentsForViewing $payments,
        OrderMessagesForViewing $messages,
        OrderPricesForViewing $prices,
        OrderDiscountsForViewing $discounts,
        OrderSourcesForViewing $sources,
        LinkedOrdersForViewing $linkedOrders,
        string $shippingAddressFormatted = '',
        string $invoiceAddressFormatted = '',
        string $note = '',
        MarketplaceCore $marketplaceCore
    ) {
        parent::__construct(
            $orderId,
            $currencyId,
            $carrierId,
            $carrierName,
            $shopId,
            $reference,
            $isVirtual,
            $taxMethod,
            $isTaxIncluded,
            $isValid,
            $hasBeenPaid,
            $hasInvoice,
            $isDelivered,
            $isShipped,
            $invoiceManagementIsEnabled,
            $createdAt,
            $customer,
            $shippingAddress,
            $invoiceAddress,
            $products,
            $history,
            $documents,
            $shipping,
            $returns,
            $payments,
            $messages,
            $prices,
            $discounts,
            $sources,
            $linkedOrders,
            $shippingAddressFormatted,
            $invoiceAddressFormatted,
            $note
        );
        $this->marketplaceCore = $marketplaceCore;
    }

    /**
     * @return MarketplaceCore
     */
    public function getMarketplaceCore(): MarketplaceCore
    {
        return $this->marketplaceCore;
    }
}
