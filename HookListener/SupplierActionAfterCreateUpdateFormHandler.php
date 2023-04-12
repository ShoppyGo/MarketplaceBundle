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

use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceCategoryRepository;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerRepository;

class SupplierActionAfterCreateUpdateFormHandler extends AbstractHookListenerImplementation
{
    protected MarketplaceCore $core;
    protected MarketplaceCategoryRepository $categoryRepository;
    protected CommandBusInterface $commandBus;
    protected MarketplaceSellerRepository $marketplaceSellerCategoryRepository;

    public function __construct(
        MarketplaceCore $core,
        CommandBusInterface $commandBus,
        MarketplaceSellerRepository $marketplaceSellerCategoryRepository
    ) {
        $this->core = $core;
        $this->commandBus = $commandBus;
        $this->marketplaceSellerCategoryRepository = $marketplaceSellerCategoryRepository;
    }

    public function exec(array $params)
    {
        $marketplace_seller_values = [];
        $marketplace_seller_values['id_seller'] = (int)$params['id'];

        if ($this->core->isEmployStaff() === true) {
            $marketplace_seller_values['id_category'] = (int)$params['form_data']['category'];
            $marketplace_seller_values['id_commission'] = (int)$params['form_data']['commission'];
        }
        $marketplace_seller_values['vat_number'] = $params['form_data']['vat_number'];
        $marketplace_seller_values['website'] = $params['form_data']['website'];
        $marketplace_seller_values['email'] = $params['form_data']['email'];
        $marketplace_seller_values['return_policy'] = $params['form_data']['return_policy'];
        $this->marketplaceSellerCategoryRepository->createOrUpdate($marketplace_seller_values);
    }
}
