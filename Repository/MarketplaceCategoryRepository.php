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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceCategory;

/**
 * @method get(string $string)
 */
class MarketplaceCategoryRepository extends EntityRepository
{
    public function create(int $id, bool $is_seller = false): MarketplaceCategory
    {
        $category = new MarketplaceCategory();
        $category->setIdCategory($id);
        $category->setSeller($is_seller);

        /* @var EntityManagerInterface $em */
        $this->getEntityManager()
            ->persist($category)
        ;
        $this->getEntityManager()
            ->flush()
        ;

        return $category;
    }

    public function findBySupplier(mixed $id_supplier): array
    {
        //todo: implementare ricerca categorie suplier
        return [];
    }

    public function findOneByShop(int $id_category): ?MarketplaceCategory
    {
        return $this->findOneBy(['id_category' => $id_category]);
    }

    public function isSellerCategory(int $id_category): bool
    {
        $marketplaceCategory = $this->findOneByShop($id_category);

        return $marketplaceCategory ? $marketplaceCategory->isSeller() : false;
    }

    public function toggle(int $id, bool $switch): bool
    {
        $record = $this->findOneBy(['id_category' => $id]);

        $record->setSeller($switch);
        $this->getEntityManager()
            ->persist($record)
        ;
        $this->getEntityManager()
            ->flush()
        ;

        return true;
    }
}
