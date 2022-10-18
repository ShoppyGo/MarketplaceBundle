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

namespace ShoppyGo\MarketplaceBundle\HookListener;

use PrestaShop\PrestaShop\Adapter\Entity\PrestaShopException;
use ShoppyGo\MarketplaceBundle\Adapter\Entity\Seller;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceEmployeeSellerRepository;
use Symfony\Component\Translation\TranslatorInterface;

class ProductsActionAdminListingResultsModifierListener extends AbstractHookListenerImplementation
{
    protected MarketplaceCore $core;
    protected MarketplaceEmployeeSellerRepository $employeeSellerRepository;
    protected TranslatorInterface $translator;

    public function __construct(
        MarketplaceCore $core,
        MarketplaceEmployeeSellerRepository $employeeSellerRepository,
        TranslatorInterface $translator
    ) {
        $this->core = $core;
        $this->employeeSellerRepository = $employeeSellerRepository;
        $this->translator = $translator;
    }

    public function exec(array $params)
    {
        if (false == $this->core->isEmployeeSeller()) {
            return;
        }
        $sql_table = &$params['sql_table'];
        $sql_where = &$params['sql_where'];
        $sql_group = &$params['sql_group_by'];

        if (array_key_exists('msp', $sql_table) === true) {
            return;
        }

        $seller = new Seller($this->core->getSellerId());
        if ($seller->active === false) {
            throw new PrestaShopException('List of products are not available. You are not  a seller.');
        }
        $sql_table['mksp'] = [
            'table' => 'product_supplier',
            'join' => 'INNER JOIN',
            'on' => 'mksp.`id_product` = p.`id_product`',
        ];
        $sql_where[] = 'mksp.`id_supplier` = ' . $seller->getSellerId();
        $sql_group[] = 'p.`id_product`';

    }
}
