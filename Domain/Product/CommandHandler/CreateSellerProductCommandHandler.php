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

namespace ShoppyGo\MarketplaceBundle\Domain\Product\CommandHandler;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\PrestaShop\Adapter\Entity\ProductSupplier;
use PrestaShop\PrestaShop\Adapter\LegacyContext;
use ShoppyGo\MarketplaceBundle\Domain\Product\Command\CreateSellerProductCommand;
use ShoppyGo\MarketplaceBundle\Domain\Seller\Exception\NotOwnerMarketplaceSellerProductException;

class CreateSellerProductCommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var LegacyContext
     */
    private $legacyContext;

    public function __construct(Registry $registry, LegacyContext $legacyContext)
    {
        $this->manager = $registry->getManager();
        $this->legacyContext = $legacyContext;
    }

    public function handle(CreateSellerProductCommand $command)
    {
        $sellerId = $command->getSellerId();
        $productId = $command->getProductId();
        $attributeId = $command->getAttributeId();

        $query = new \DbQuery();
        $query->select('ps.id_supplier');
        $query->from('product_supplier', 'ps');
        $query->where('ps.id_product = ' . (int) $productId);
        $id_ps = \Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);

        if ($id_ps) {
            //
            // esiste ma appartiene al seller?
            //
            $id = ProductSupplier::getIdByProductAndSupplier($productId, 0, $sellerId);
            if (!$id) {
                throw new NotOwnerMarketplaceSellerProductException('Seller not own the product');
            }
            //
            // l'abbinamento al supplier esiste già
            //
            return;
        }

        //--- il prodotto deve essere semre agganciato all'anagrafica supplier
        $product_supplier = new ProductSupplier();
        $product_supplier->id_supplier = $sellerId;
        $product_supplier->id_product = $productId;
        $product_supplier->id_currency = $this->legacyContext->getContext()->currency->id;
        $product_supplier->id_product_attribute = $attributeId;
        $product_supplier->save();
    }
}
