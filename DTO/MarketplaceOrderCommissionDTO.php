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

namespace ShoppyGo\MarketplaceBundle\DTO;

class MarketplaceOrderCommissionDTO
{
    public int $id_order = 0;
    public int $id_currency = 0;
    public float $total_discounts = 0;
    public float $total_discounts_tax_incl = 0;
    public float $total_discounts_tax_excl = 0;
    public float $total_paid = 0;
    public float $total_paid_tax_incl = 0;
    public float $total_paid_tax_excl = 0;
    public float $total_paid_real = 0;
    public float $total_products = 0;
    public float $total_products_wt = 0;
    public float $total_shipping = 0;
    public float $total_shipping_tax_incl = 0;
    public float $total_shipping_tax_excl = 0;
    public float $carrier_tax_rate = 0;
    public float $total_wrapping = 0;
    public float $total_wrapping_tax_incl = 0;
    public float $total_wrapping_tax_excl = 0;
    public int $round_mode = 0;
    public int $round_type = 0;

}
