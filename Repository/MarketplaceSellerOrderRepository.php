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
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSellerOrder;

class MarketplaceSellerOrderRepository extends EntityRepository
{
    protected MarketplaceSellerProductRepository $marketplaceSellerProductRepository;
    /**
     * @var int
     */
    private $id_seller;

    public function getLastMainOrder(): int
    {
        $res = $this->createQueryBuilder('so')
            ->select('so.id_order_main')
            ->orderBy('so.id_order_main', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->execute()
        ;

        return $res['id_main_order'] ?? 0;
    }

    public function getLastSellerOrder(): int
    {
        $res = $this->createQueryBuilder('so')
            ->select('so.id_order')
            ->orderBy('so.id_order', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->execute()
        ;

        return $res['id_order'] ?? 0;
    }

    /**
     * restituisce i seller legati ai prodotti
     *
     * @param $products
     *
     * @return array<int>
     */
    public function getSellers(array $products): array
    {
        $id_products = [];
        foreach ($products as $product) {
            $id_products[] = $product['id_product'];
        }

        //---- recupero l'abbinamento seller prodotti in base alla lista dei prodotti
        $sellers = $this->marketplaceSellerProductRepository->findSellersByProducts($id_products);

        //---- se non esiste nessun abbinamento, restituisco un insieme vuoto
        return !$sellers ? [] : $sellers;
    }

    public function hasSellerProduct($id_product): bool
    {
        return (bool)$this->marketplaceSellerProductRepository->isProductSeller($id_product, $this->id_seller);
    }

    public function isSellerOrder(int $id_order, int $id_seller): bool
    {
        return (bool)$this->findOneBy(['id_order' => $id_order, 'id_seller' => $id_seller]);
    }

    public function save(MarketplaceSellerOrder $sellerOrder)
    {
        $this->getEntityManager()
            ->persist($sellerOrder)
        ;
        $this->getEntityManager()
            ->flush()
        ;
    }

    /**
     * @param int $id_seller
     */
    public function setIdSeller(int $id_seller)
    {
        $this->id_seller = $id_seller;
    }

    public function setSellerProductRepository(MarketplaceSellerProductRepository $marketplaceSellerProductRepository)
    {
        $this->marketplaceSellerProductRepository = $marketplaceSellerProductRepository;
    }

}
