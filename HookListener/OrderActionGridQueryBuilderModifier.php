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

use Doctrine\ORM\QueryBuilder;
use PrestaShop\PrestaShop\Core\Search\Filters\OrderFilters;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderActionGridQueryBuilderModifier extends AbstractHookListenerImplementation
{
    protected MarketplaceCore $core;
    protected TranslatorInterface $translator;

    public function __construct(
        MarketplaceCore $core,
        TranslatorInterface $translator
    ) {
        $this->core = $core;
        $this->translator = $translator;
    }

    public function exec(array $params): void
    {xdebug_break();
        /** @var QueryBuilder $qb */
        $qb = $params['search_query_builder'];
        $qb->addSelect('
             ms.`id_supplier` as `id_seller`, ms.`name` as `seller_name`, 0 as `commission_amount`,
             o.`total_discounts`, o.`total_discounts_tax_incl`, o.`total_discounts_tax_excl`,
             o.`total_paid`, o.`total_paid_tax_excl`, o.`total_paid_real`, o.`total_products`,
             o.`total_products_wt`, o.`total_shipping`, o.`total_shipping_tax_incl`,
             o.`total_shipping_tax_excl`, o.`carrier_tax_rate`, o.`total_wrapping`,
             o.`total_wrapping_tax_incl`, o.`total_wrapping_tax_excl`, o.`round_mode`
        ')
            ->leftJoin('o', _DB_PREFIX_.'marketplace_seller_order', 'mp', 'o.id_order = mp.id_order')
            ->leftJoin('mp', _DB_PREFIX_.'supplier', 'ms', 'mp.id_supplier = ms.id_supplier')
        ;
        if (false === $this->core->isEmployStaff()) {
            $qb->andWhere('ms.id_supplier = :id_seller')
                ->setParameter('id_seller', $this->core->getSellerId())
            ;
        }
        /** @var OrderFilters $order_filters */
        $filters = $params['search_criteria']->getFilters();
        if(true === isset($filters['seller_name'])){
            $qb->andWhere('ms.name LIKE :seller_name')
                ->setParameter('seller_name', '%'.$filters['seller_name'].'%');
        }
    }
}
