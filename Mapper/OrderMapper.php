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

namespace ShoppyGo\MarketplaceBundle\Mapper;

use PrestaShop\PrestaShop\Adapter\Entity\Order;
use ShoppyGo\MarketplaceBundle\DTO\MarketplaceOrderCommissionDTO;

class OrderMapper
{
    public static function mapArrayToMarketplaceOrderCommissionDTO(array $data): MarketplaceOrderCommissionDTO
    {
        $marketplaceOrder = new MarketplaceOrderCommissionDTO();

        // loop attraverso le chiavi dell'array
        foreach ($data as $key => $value) {
            // se la chiave esiste nella DTO, impostala con il valore corrispondente nell'array
            if (property_exists($marketplaceOrder, $key)) {
                $marketplaceOrder->{$key} = $value;
            }
        }

        return $marketplaceOrder;
    }

    public static function mapObjecttoMarketplaceOrderCommissionDTO(Order $order): MarketplaceOrderCommissionDTO
    {
        $marketplaceOrder = new MarketplaceOrderCommissionDTO();

        foreach (get_object_vars($order) as $propName => $propValue) {
            if (property_exists($marketplaceOrder, $propName)) {
                $marketplaceOrder->{$propName} = $propValue;
            }
        }

        return $marketplaceOrder;
    }
}
