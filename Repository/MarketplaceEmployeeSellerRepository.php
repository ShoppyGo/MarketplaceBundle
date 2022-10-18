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

namespace  ShoppyGo\MarketplaceBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceEmployeeSeller;
use ShoppyGo\MarketplaceBundle\Interfaces\MarketplaceSellerRepositoryInterface;

/**
 * @method get(string $string)
 */
class MarketplaceEmployeeSellerRepository extends EntityRepository
{

    public function findSellersByEmployees(array $employees): array
    {
        return $this->createQueryBuilder('ms')
            ->where('ms.id_employee in (:employee)')
            ->setParameters([':employee' => $employees])
            ->getQuery()->execute();

    }

    public function create(int $id_employee, int $id_seller)
    {
        $seller = new MarketplaceEmployeeSeller();
        $seller->setIdEmployee($id_employee);
        $seller->setIdSeller($id_seller);

        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->persist($seller);
        $em->flush();
    }
    public function update(int $id_employee, int $id_seller)
    {
        $employee_seller = $this->find($id_employee);
        $employee_seller->setIdSeller($id_seller);
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->persist($employee_seller);
        $em->flush();
    }

    public function findSellerByEmployee(?int $id_employee): ?MarketplaceEmployeeSeller
    {
        return $this->findOneBy(['id_employee' => $id_employee]);
    }
}
