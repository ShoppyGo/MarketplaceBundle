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

namespace ShoppyGo\MarketplaceBundle\Repository;

use Doctrine\ORM\EntityRepository;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSellerShipping;

class MarketplaceSellerShippingRepository extends EntityRepository
{

    public function findRange(int $id_seller, $total): ?MarketplaceSellerShipping
    {
        $range = $this->createQueryBuilder('s')
            ->where('s.from between :from and :to')
            ->orWhere('s.from < :total and s.to > :total')
            ->andWhere('s.id_seller = :seller')
            ->setParameters(['total' => $total, 'seller' => $id_seller])
            ->orderBy('s.cost', 'DESC')
            ->getQuery()
            ->execute()
        ;
        if (count($range) > 0) {
            return $range[0];
        }
        #
        # non trovo nulla, quindi estraggo il record più grande
        #
        $range = $this->createQueryBuilder('s')
            ->where('s.from between :from and :to')
            ->orWhere('s.to < :total ')
            ->andWhere('s.id_seller = :seller')
            ->setParameters(['total' => $total, 'seller' => $id_seller])
            ->orderBy('s.cost', 'DESC')
            ->getQuery()
            ->execute()
        ;

        if (count($range) > 0) {
            return $range[0];
        }
        #
        # se non trovo nulla restutisco un insieme vuoto
        #
        return null;
    }

    /**
     * @param $from
     * @param $to
     * @return array<MarketplaceSellerShipping>
     */
    public function getRanges($from, $to, $id_seller): array
    {
        $range1 = $this->createQueryBuilder('s')
            ->where('s.from between :from and :to')
            ->orWhere('s.to between :from and :to')
            ->andWhere('s.id_seller = :seller')
            ->setParameters(['from' => $from, 'to' => $to, 'seller' => $id_seller])
            ->getQuery()
            ->execute()
        ;
        $range2 = $this->createQueryBuilder('s')
            ->where('s.from < :from and s.to > :from')
            ->orWhere('s.from < :to and s.to > :to')
            ->andWhere('s.id_seller = :seller')
            ->setParameters(['from' => $from, 'to' => $to, 'seller' => $id_seller])
            ->getQuery()
            ->execute()
        ;

        return array_merge($range1, $range2);
    }

}
