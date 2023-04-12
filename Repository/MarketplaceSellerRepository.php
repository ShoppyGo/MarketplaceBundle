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
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceCommission;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSeller;

class MarketplaceSellerRepository extends EntityRepository
{
    public function create(array $values, MarketplaceCommission $commission): ?MarketplaceSeller
    {
        $entity = new MarketplaceSeller();
        $entity->setIdSeller($values['id_seller']);
        $entity->setIdCategory($values['id_category']);
        $entity->setMarketplaceCommission($commission);
        $entity->setWebsite($values['website']);
        $entity->setVatNumber($values['vat_number']);
        $entity->setReturnPolicy($values['return_policy']);
        $entity->setEmail($values['email']);

        $this->getEntityManager()
            ->persist($entity)
        ;
        $this->getEntityManager()
            ->flush()
        ;

        return $entity;
    }

    public function createOrUpdate(array $values): MarketplaceSeller
    {
        $commission = isset($values['id_commission']) ? $this->getEntityManager()
            ->getRepository(MarketplaceCommission::class)
            ->find($values['id_commission']) : null;
        $marketplaceSellerCategory = $this->update($values, $commission);
        if (!$marketplaceSellerCategory) {
            $marketplaceSellerCategory = $this->create($values, $commission);
        }

        return $marketplaceSellerCategory;
    }

    public function findSellerCategoryRootBy(int $id_seller): ?MarketplaceSeller
    {
        return $this->findOneBy([
            'id_seller' => $id_seller,
        ]);
    }

    public function update(array $values, ?MarketplaceCommission $commission): ?MarketplaceSeller
    {
        $entity = $this->findOneBy(['id_seller' => $values['id_seller']]);
        if (!$entity) {
            return null;
        }
        if (isset($values['id_category'])) {
            $entity->setIdCategory($values['id_category']);
        }
        if ($commission) {
            $entity->setMarketplaceCommission($commission);
        }
        $entity->setVatNumber($values['vat_number']);
        $entity->setWebsite($values['website']);
        $entity->setReturnPolicy($values['return_policy']);
        $entity->setEmail($values['email']);
        $this->getEntityManager()
            ->flush()
        ;

        return $entity;
    }
}
