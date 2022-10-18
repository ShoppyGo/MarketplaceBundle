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

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSellerProduct;

class MarketplaceSellerProductRepository
{
    public function __construct(
        private Connection $connection,
        private string $dbPrefix
    )
    {
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
        $where = 'ps.id_product in (';
        $last_index = count($id_products) - 1;
        foreach ($id_products as $k => $idProduct) {
            $where .= $idProduct;
            if ($last_index !== $k) {
                $where .= ',';
            }
        }
        $where .= ')';
        $sellers = $this->connection->createQueryBuilder()
            ->from($this->dbPrefix.'product_supplier', 'ps')
            ->select('distinct(ps.id_supplier) as seller')
            ->andWhere($where)
            ->setParameter('products', $id_products)
            ->execute()
            ->fetchAllAssociative()
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

    public function isProductSeller($id_product, int $id_seller): bool
    {
        $res = $this->connection->createQueryBuilder()
            ->from($this->dbPrefix.'product_supplier', 'ps')
            ->select('distinct(ps.id_supplier) as seller')
            ->andWhere('ps.id_product = :product')
            ->andWhere('ps.id_supplier = :seller')
            ->setParameter('product', $id_product)
            ->setParameter('seller', $id_seller)
            ->execute()->fetchOne();
        return (bool) $res;
    }
}
