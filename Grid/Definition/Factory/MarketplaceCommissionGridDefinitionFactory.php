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

use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Grid\Column\Type\TickColumn;

class MarketplaceCommissionGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    public const GRID_ID = 'marketplace_commission';

    public function setMarketplaceCore(MarketplaceCore $marketplaceCore)
    {
        $this->marketplaceCore = $marketplaceCore;
    }

    protected function getColumns()
    {
        $columns = (new ColumnCollection())->add(
            (new DataColumn('commission_name'))->setName($this->trans('Commission Name', [], 'Admin.Global'))
                ->setOptions([
                    'field' => 'commission_name',
                ])
        )
            ->add(
                (new DataColumn('fixed_commission'))->setName($this->trans('Fixed Commission', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'fixed_commission',
                    ])
            )
            ->add(
                (new DataColumn('commission_percentage'))->setName(
                    $this->trans('Commission Percentage', [], 'Admin.Global')
                )
                    ->setOptions([
                        'field' => 'commission_percentage',
                    ])
            )
            ->add(
                (new TickColumn('total_products_net_of_vat'))->setName(
                    $this->trans('Total of product (net Vat)', [], 'Admin.Global')
                )
                    ->setOptions([
                        'field' => 'total_products_net_of_vat',
                    ]),
            )
            ->add(
                (new TickColumn('total_net_of_discount'))->setName(
                    $this->trans('Total net of discount', [], 'Admin.Global')
                )
                    ->setOptions([
                        'field' => 'total_net_of_discount',
                    ])
            )
            ->add(
                (new TickColumn('shipping_value_net_of_vat'))->setName(
                    $this->trans('Shipping net of Vat', [], 'Admin.Global')
                )
                    ->setOptions([
                        'field' => 'shipping_value_net_of_vat',
                    ])
            )
            ->add(
                (new TickColumn('total_vat'))->setName($this->trans('Total Vat', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'total_vat',
                    ])
            )
            ->add(
                (new TickColumn('total_general'))->setName($this->trans('Total General', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'total_general',
                    ])
            )
            ->add(
                (new ActionColumn('actions'))->setName(
                    $this->trans('Actions', [], 'Admin.Global')
                )
                    ->setOptions([
                        'actions' => $this->getRowActions(),
                    ])
            )
        ;

        return $columns;
    }

    protected function getFilters()
    {
        $filters = (new FilterCollection())
//        ->add(...)
            // Add the rest of your filters here
        ;

        return $filters;
    }

    protected function getGridActions()
    {
        $gridActions = (new GridActionCollection())
//        ->add(...)// Add the rest of your grid actions here
        ;

        return $gridActions;
    }

    protected function getId()
    {
        return self::GRID_ID;
    }

    protected function getName()
    {
        return $this->trans('Marketplace Commissions', [], 'Admin.Global');
    }

    private function getRowActions(): RowActionCollection
    {
        return (new RowActionCollection())->add(
            (new LinkRowAction('edit'))->setName(
                $this->trans('Edit', [], 'Admin.Actions')
            )
                ->setIcon('edit')
                ->setOptions([
                    'route' => 'admin_marketplace_marketplace_commission_edit',
                    'route_param_name' => 'id_marketplace_commission',
                    'route_param_field' => 'id_marketplace_commission',
                    'clickable_row' => true,
                ])
        )
            ->add(
                (new SubmitRowAction('delete'))->setName(
                    $this->trans('Delete', [], 'Admin.Actions')
                )
                    ->setIcon('delete')
                    ->setOptions([
                        'method' => 'DELETE',
                        'route' => 'admin_marketplace_marketplace_commission_delete',
                        'route_param_name' => 'id_marketplace_commission',
                        'route_param_field' => 'id_marketplace_commission',
                        'confirm_message' => $this->trans(
                            'Delete selected item?',
                            [],
                            'Admin.Notifications.Warning'
                        ),
                    ])
            )
        ;
    }
}
