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

namespace ShoppyGo\MarketplaceBundle\Grid\Query;

use Doctrine\DBAL\Connection;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;

class MarketplaceSellerShippingQueryBuilder extends AbstractDoctrineQueryBuilder
{
    protected MarketplaceCore $core;

    public function __construct(Connection $connection, $dbPrefix, MarketplaceCore $core)
    {
        parent::__construct($connection, $dbPrefix);

        $this->core = $core;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getBaseQuery();
        $qb->select('COUNT(mp.id_shipping)');

        return $qb;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        //
        // non ha parametri di ricerca
        //

        return $this->getBaseQuery()->select('mp.*');
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    private function getBaseQuery()
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix . 'marketplace_seller_shipping', 'mp');

        if ($this->core->isEmployStaff() === false) {
            $qb->andWhere('mp.id_employee = :id_seller')
                ->setParameter('id_seller', $this->core->getEmployee()->id);
        }
        $qb->orderBy('mp.from_total');

        return $qb;
    }
}
