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

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSellerProduct;

class MarketplaceSellerProductRepositoryOld
{
    protected Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function createProduct(int $id_seller, int $id_product)
    {
        $seller_product = new MarketplaceSellerProduct();
        $seller_product->setIdSeller($id_seller);
        $seller_product->setIdProduct($id_product);

        /** @var EntityManagerInterface $em */
        $em = $this->registry->getManager();
        $em->persist($seller_product);
        $em->flush();
    }

    public function findProduct($id_product): ?MarketplaceSellerProduct
    {
        return $this->findOneBy(['id_product' => $id_product]);
    }

    // recupero lista prodotti per seller
    public function findSellersByProducts(array $id_products): array
    {
        $sellers = $this->createQueryBuilder('ps')
            ->select('distinct(ps.id_seller) as seller')
            ->andWhere('ps.id_product in (:products)')
            ->setParameter('products', $id_products)
            ->getQuery()
            ->execute()
        ;

        $id_seller = [];

        if (!$sellers) {
            return $id_seller;
        }

        foreach ($sellers as $seller) {
            $id_seller[] = $seller['seller'];
        }

        return $id_seller;
    }
}
