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

namespace ShoppyGo\MarketplaceBundle\Controller;

use PrestaShop\PrestaShop\Core\Search\Filters\OrderFilters;
use PrestaShopBundle\Component\CsvResponse;
use PrestaShopBundle\Controller\Admin\Sell\Order\OrderController;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;

class MarketplaceSellerOrderController extends OrderController
{
    protected MarketplaceCore $marketplaceCore;

    public function __construct()
    {
        parent::__construct();
    }

    public function setCore(MarketplaceCore $marketplaceCore)
    {
        $this->marketplaceCore = $marketplaceCore;
    }
    public function exportAction(OrderFilters $filters)
    {
        $isB2bEnabled = $this->get('prestashop.adapter.legacy.configuration')->get('PS_B2B_ENABLE');

        $filters = new OrderFilters(['limit' => null] + $filters->all());
        $orderGrid = $this->get('prestashop.core.grid.factory.order')->getGrid($filters);

        $headers = [
            'id_order' => $this->trans('ID', 'Admin.Global'),
            'reference' => $this->trans('Reference', 'Admin.Global'),
//
            'country_name' => $this->trans('Delivery', 'Admin.Global'),
            'customer' => $this->trans('Customer', 'Admin.Global'),
            'total_paid_tax_excl' => $this->trans('Total', 'Admin.Global'),
            'total_paid_tax_incl' => $this->trans('Total with VAT', 'Admin.Global'),
            'commission_amount' => $this->trans('Commission amount', 'Admin.Global'),
            'osname' => $this->trans('Status', 'Admin.Global'),
            'date_add' => $this->trans('Date', 'Admin.Global'),
        ];

        if($this->marketplaceCore->isEmployStaff() === true){
            $headers['new'] =  $this->trans('New client', 'Admin.Orderscustomers.Feature');
            $headers['payment'] =$this->trans('Payment', 'Admin.Global');
        }
        if ($isB2bEnabled) {
            $headers['company'] = $this->trans('Company', 'Admin.Global');
        }

        $data = [];

        foreach ($orderGrid->getData()->getRecords()->all() as $record) {
            $item = [
                'id_order' => $record['id_order'],
                'reference' => $record['reference'],
                'country_name' => $record['country_name'],
                'customer' => $record['customer'],
                'total_paid_tax_excl' => $record['total_paid_tax_excl'],
                'total_paid_tax_incl' => $record['total_paid_tax_incl'],
                'commission_amount' => $record['commission_amount'],
                'osname' => $record['osname'],
                'date_add' => $record['date_add'],
            ];
            if($this->marketplaceCore->isEmployStaff() === true){
                $item['new'] =  $record['new'];
                $item['payment'] = $record['payment'];
            }
            if ($isB2bEnabled) {
                $item['company'] = $record['company'];
            }

            $data[] = $item;
        }

        return (new CsvResponse())
            ->setData($data)
            ->setHeadersData($headers)
            ->setFileName('order_' . date('Y-m-d_His') . '.csv');
    }

}
