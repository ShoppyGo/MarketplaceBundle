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

namespace ShoppyGo\MarketplaceBundle\Domain\Seller\CommandHandler;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use ShoppyGo\MarketplaceBundle\Domain\Seller\Command\ReplaceSellerCommand;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSeller;

class ReplaceSellerCommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(Registry $registry)
    {
        $this->manager = $registry->getManager();
    }

    public function handle(ReplaceSellerCommand $command)
    {
        $repo = $this->manager->getRepository($command->getEntityName());
        /** @var MarketplaceSeller $seller */
        $seller = $repo->findOneBy([$command->getFieldName() => $command->getId()]);
        if (!$seller) {
            $entity_name = $command->getEntityName();
            $seller = new $entity_name();
            $seller->setId($command->getId());
            if ($command->getIdShop()) {
                $seller->setIdShop($command->getIdShop());
            }
        }
//        $seller->setSeller((bool)$command->getSeller());
        $params = $command->getParams();
        $seller->setIdSupplier($params['supplier']);
        $this->manager->persist($seller);
        $this->manager->flush();
    }
}
