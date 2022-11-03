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

use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceSellerOrderManager;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerOrderStatusRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderActionAdminStatusListingFiledsModifier extends AbstractHookListenerImplementation
{

    public function __construct(
        private MarketplaceCore $core,
        private TranslatorInterface $translator,
        private MarketplaceSellerOrderStatusRepository $orderStatusRepository
    ) {
    }

    public function exec(array $params)
    {
        $params['fields']['is_seller'] = [
            'title'   => $this->translator->trans('Is seller', [], 'Admin.Global'),
            'align'   => 'text-center',
            'active'  => 'delivery',
            'type'    => 'bool',
            'orderby' => false,
            'class'   => 'fixed-width-sm',
        ];
    }

}
