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

class OrderActionStatusFormModifier extends AbstractHookListenerImplementation
{

    public function __construct(
        private MarketplaceCore $core,
        private TranslatorInterface $translator,
        private MarketplaceSellerOrderStatusRepository $orderStatusRepository
    ) {
    }

    public function exec(array $params)
    {
        // only when update because Legacy AdminStatusesController
        //   has not dispatch
        /** @var \OrderState $order_state */
        $object = $params['object'];
        if (false === (bool)$object->id) {
            return;
        }

        $seller_order_status = $this->orderStatusRepository->find($object->id);

        $params['fields_value']['is_seller'] = $seller_order_status ? (int)$seller_order_status->isSeller() : 0;

        $params['fields'][]['form'] = [
            'tinymce' => true,
            'legend'  => [
                'title' => $this->trans('Marketplace', [], 'Admin.Shoppygo.Marketplace'),
                'icon'  => 'icon-time',
            ],
            'input'   => [
                'is_seller' => [
                    'type'   => 'switch',
                    'label'  => $this->trans('Is a seller order state', [], 'Admin.Shoppygo.Marketplace'),
                    'name'   => 'is_seller',
                    'class'  => 't',
                    'values' => [
                        [
                            'id'    => 'is_seller_on',
                            'value' => 1,
                            'label' => $this->trans('Yes', [], 'Admin.Global'),
                        ],
                        [
                            'id'    => 'is_seller_off',
                            'value' => 0,
                            'label' => $this->trans('No', [], 'Admin.Global'),
                        ],
                    ],
                ],
            ],
            'submit'  => [
                'title' => $this->trans('Save', [], 'Admin.Actions'),
            ],
        ];
    }

    private function trans(string $id, array $params, string $domain): string
    {
        return $this->translator->trans($id, $params, $domain);
    }

}
