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

use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceCommissionRepository;

class MarketplaceCommissionQueryBuilder extends AbstractDoctrineQueryBuilder
{
    protected MarketplaceCommissionRepository $commissionRepository;

    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getBaseQueryBuilder();
        $qb->select('COUNT(mc.id_marketplace_commission)');

        return $qb;
    }

    private function getBaseQueryBuilder()
    {
        $queryBuilder = $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix . 'marketplace_commission', 'mc')
        ;

        return $queryBuilder;
    }

    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $queryBuilder = $this->getBaseQueryBuilder()->select('mc.*');

        // Add filters if any
        foreach ($searchCriteria->getFilters() as $filterName => $filterValue) {
            $queryBuilder->andWhere(sprintf('mc.%s = :%s', $filterName, $filterName))
                ->setParameter($filterName, $filterValue)
            ;
        }

        // Add sorting
        $orderBy = $searchCriteria->getOrderBy();
        $orderWay = $searchCriteria->getOrderWay();
        if ($orderBy && $orderWay) {
            $queryBuilder->addOrderBy(sprintf('mc.%s', $orderBy), $orderWay);
        }

        // Add pagination
        $queryBuilder->setFirstResult($searchCriteria->getOffset());
        $queryBuilder->setMaxResults($searchCriteria->getLimit());

        return $queryBuilder;
    }

    public function setMarketplaceCore(MarketplaceCore $marketplaceCore)
    {
        $this->marketplaceCore = $marketplaceCore;
    }

    public function setRepositoryMarketplaceCommision(MarketplaceCommissionRepository $commissionRepository)
    {
        $this->commissionRepository = $commissionRepository;
    }
}
