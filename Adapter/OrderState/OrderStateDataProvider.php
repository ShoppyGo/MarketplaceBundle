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

namespace ShoppyGo\MarketplaceBundle\Adapter\OrderState;

use OrderState;
use PrestaShop\PrestaShop\Core\Order\OrderStateDataProviderInterface;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerOrderStatusRepository;

/**
 * Class OrderStateDataProvider provides OrderState data using legacy code.
 */
final class OrderStateDataProvider implements OrderStateDataProviderInterface
{
    protected MarketplaceSellerOrderStatusRepository $orderStatusRepo;
    protected MarketplaceCore $core;

    /**
     * {@inheritdoc}
     */
    public function getOrderStates($languageId)
    {
        if ($this->core->isEmployStaff()) {
            return OrderState::getOrderStates($languageId, false);
        }
        $statuses = OrderState::getOrderStates($languageId, false);
        foreach ($statuses as $key => $status) {
            $seller_status = $this->orderStatusRepo->find($status['id_order_state']);
            if(null === $seller_status || false === $seller_status->isSeller()) {
                unset($statuses[$key]);
            }
        }

        return $statuses;
    }

    public function setCore(MarketplaceCore $core)
    {
        $this->core = $core;
    }

    public function setOrderStatusRepository(MarketplaceSellerOrderStatusRepository $orderStatusRepository)
    {
        $this->orderStatusRepo = $orderStatusRepository;
    }
}
