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

namespace ShoppyGo\MarketplaceBundle\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Hook\HookDispatcherInterface;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;

class MarketplaceSellerShippingGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    public const DOMAIN_TRANSLATION = 'Admin.Marketplace.Seller.Shipping';
    protected MarketplaceCore $core;

    public function __construct(HookDispatcherInterface $hookDispatcher = null, MarketplaceCore $core)
    {
        parent::__construct(
            $hookDispatcher
        );
        $this->core = $core;
    }

    protected function getId()
    {
        return 'seller_shipping';
    }

    protected function getName()
    {
        return $this->trans('Seller shipping', [], self::DOMAIN_TRANSLATION);
    }

    protected function getColumns()
    {
        $column_collection = new ColumnCollection();

        if (true === $this->core->isEmployStaff()) {
            $id_seller = new DataColumn('name');
            $id_seller->setOptions(['field' => 'name'])
                ->setName('Seller', [], self::DOMAIN_TRANSLATION)
         ;
            $column_collection->add($id_seller);
        }

        $from = new DataColumn('from_total');
        $from->setOptions(['field' => 'from_total'])
            ->setName('From', [], self::DOMAIN_TRANSLATION);
        $column_collection->add($from);

        $to = new DataColumn('to_total');
        $to->setOptions(['field' => 'to_total'])
            ->setName('To', [], self::DOMAIN_TRANSLATION);
        $column_collection->add($to);

        $cost = new DataColumn('shipping_cost');
        $cost->setOptions(['field' => 'shipping_cost'])
            ->setName('Cost', [], self::DOMAIN_TRANSLATION);
        $column_collection->add($cost);

        $row_actions = new RowActionCollection();
        $row_actions->add(
            (new LinkRowAction('edit'))
                ->setIcon('edit')
                ->setName('Edit', [], self::DOMAIN_TRANSLATION)
                ->setOptions(
                    [
                        'route' => 'admin_marketplace_seller_shipping_edit',
                        'route_param_name' => 'id_shipping',
                        'route_param_field' => 'id_shipping',
                    ]
                )
        );
        $row_actions->add(
            (new LinkRowAction('delete'))
                ->setIcon('delete')
                ->setName('Delete', [], self::DOMAIN_TRANSLATION)
                ->setOptions(
                    [
                        'route' => 'admin_marketplace_seller_shipping_delete',
                        'route_param_name' => 'id_shipping',
                        'route_param_field' => 'id_shipping',
                        'confirm_message' => $this->trans(
                            'Delete selected item?',
                            [],
                            'Admin.Notifications.Warning'
                        ),
                    ]
                )
        );
        $action = new ActionColumn('actions');
        $action->setName($this->trans('Actions', [], self::DOMAIN_TRANSLATION));
        $action->setOptions(
            [
                'actions' => $row_actions,
            ]
        );

        $column_collection->add($action);

        return $column_collection;
    }
}
