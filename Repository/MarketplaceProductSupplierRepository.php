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
use PrestaShop\PrestaShop\Adapter\Product\Repository\ProductSupplierRepository;
use PrestaShop\PrestaShop\Adapter\Product\Validate\ProductSupplierValidator;

class MarketplaceProductSupplierRepository extends ProductSupplierRepository
{
    protected Connection $connection;
    protected string $dbPrefix;

    public function __construct(
        Connection $connection,
        string $dbPrefix,
        ProductSupplierValidator $productSupplierValidator
    ) {
        parent::__construct($connection, $dbPrefix, $productSupplierValidator);
        $this->connection = $connection;
        $this->dbPrefix = $dbPrefix;
    }

    public function isSellerProduct(int $id_product, $id_supplier): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $res = $qb->select('ps.id_supplier ')
            ->from($this->dbPrefix . 'product_supplier', 'ps')
            ->where('ps.id_product = :id_product and ps.id_supplier = :id_supplier')
            ->setParameters([
                'id_product' => $id_product,
                'id_supplier' => $id_supplier,
            ])->execute()->fetchOne();
        if (false === $res) {
            return false;
        }

        return (bool) $res;
    }
}
